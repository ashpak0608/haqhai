<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AroundPropertyAmenityMasterModel extends Model
{
    use HasFactory;

    protected $table = 'around_property_amenity_master';

    protected $fillable = [
        'id', 'around_amenity_name','status', 'created_by', 'created_at', 'updated_by', 'updated_at'];

    public function getSaveData() {
        return array('id', 'around_amenity_name','status', 'created_by', 'created_at', 'updated_by', 'updated_at');
    }

    public function saveData($post) {
        $saveFields = $this->getSaveData();
        $finalData = new AroundPropertyAmenityMasterModel;
        foreach ($post as $k => $v) {
            if (in_array($k, $saveFields)) {
                $finalData[$k] = $v;
            }
        }
        if (isset($finalData['id'])) {
            $id = (int) $finalData['id'];
        } else {
            $id = 0;
            unset($finalData['id']);
        }

        if ($id == 0) {
            $finalData['created_at'] = date("Y-m-d H:i:s");
            $finalData['created_by'] = Session::get('id');
            $finalData['updated_at'] = null;
            $finalData->save();
            $id = $finalData->id;
            return array('id' => $id, 'status' => 'success', 'message' => "Around Property Amenity Data saved!");
        } else {
            if ($this->getSingleData($id)) {
                $finalData['updated_at'] = date("Y-m-d H:i:s");
                $finalData['updated_by'] = Session::get('id');
                $finalData->exists = true;
                $finalData->id = $id;
                $finalData->save();
                return array('id' => $id, 'status' => 'success', 'message' => "Around Property Amenity Data updated!");
            } else {
                return false;
            }
        }
    }

    public function getSingleData($id) {
        $id = (int) $id;
        $result = DB::select("SELECT c.* FROM " . $this->table . " as c WHERE c.id=$id");
        foreach ($result as $data) {
            return json_decode(json_encode($data), True);
        }

        return false;
    }

    static function getAllAroundPropertyAmenityDetails($param = []){
       $query = DB::table('around_property_amenity_master as c');
       $query->leftjoin('users as u','c.created_by','=','u.id');
       $query->leftjoin('users as u1','c.updated_by','=','u1.id');
       $query->select(DB::raw("
        c.id,
        c.around_amenity_name,
        c.status,
        ifnull(u.user_name,'') as created_by,
        ifnull(date_format(c.created_at,'%d-%m-%Y %h:%m %p'),'') as created_at,
        ifnull(u1.user_name,'') as updated_by,
        ifnull(date_format(c.updated_at,'%d-%m-%Y %h:%m %p'),'') as updated_at"));
        if(isset($param['status']) && (in_array($param['status'],[0,1]))){
            $query->where('c.status',$param['status']);
        }
        if(isset($param['id']) && !empty($param['id'])){
            $query->where('c.id',$param['id']); 
        }
        if(isset($param['around_amenity_name']) && !empty($param['around_amenity_name'])){
            $query->where('c.around_amenity_name','like','%'.$param['around_amenity_name'].'%'); 
        }
        $total_count = $query->count();
        if(isset($param['limit']) && isset($param['start'])){
            $query->limit($param['limit'])->offset($param['start']);
        }
        $query->orderBy('c.id','desc');
        $result = $query->get();
        if($total_count > 0){
            return array('total_count'=>$total_count,'data'=>$result);
        }else{
            return array('total_count'=>0,'data'=>[]);
        }
    }
}
