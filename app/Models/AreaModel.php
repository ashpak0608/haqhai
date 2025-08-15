<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AreaModel extends Model
{
    use HasFactory;

    protected $table = 'areas';

    protected $fillable = [
        'id','location_id', 'area_name', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'
    ];

    public function getSaveData() {
        return array(
            'id','location_id', 'area_name', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'
        );
    }

    public function saveData($post) {
        $saveFields = $this->getSaveData();
        $finalData = new AreaModel;
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
            return array('id' => $id, 'status' => 'success', 'message' => "Area Data saved!");
        } else {
            if ($this->getSingleData($id)) {
                $finalData['updated_at'] = date("Y-m-d H:i:s");
                $finalData['updated_by'] = Session::get('id');
                $finalData->exists = true;
                $finalData->id = $id;
                $finalData->save();
                return array('id' => $id, 'status' => 'success', 'message' => "Area Data updated!");
            } else {
                return false;
            }
        }
    }

    public function getSingleData($id) {
        $id = (int) $id;
        $result = DB::select("SELECT a.* FROM " . $this->table . " as a WHERE a.id=$id");
        foreach ($result as $data) {
            return json_decode(json_encode($data), True);
        }

        return false;
    }

    static function details($param = []){
       $query = DB::table('areas as a');
       $query->leftjoin('users as u','a.created_by','=','u.id');
       $query->leftjoin('users as u1','a.updated_by','=','u1.id');
       $query->join('locations as l','a.location_id','=','l.id');
       $query->select(DB::raw("
        a.id,
        a.area_name,
        a.status,
        l.location_name,
        ifnull(a.area_name,'') as area_name,
        ifnull(u.full_name,'') as created_by,
        ifnull(date_format(a.created_at,'%d-%m-%Y %h:%m %p'),'') as created_at,
        ifnull(u1.full_name,'') as updated_by,
        ifnull(date_format(a.updated_at,'%d-%m-%Y %h:%m %p'),'') as updated_at"));
        if(isset($param['status']) && (in_array($param['status'],[0,1]))){
            $query->where('a.status',$param['status']);
        }
        if(!empty($param['id'])){
            $query->where('a.id',$param['id']);
        }
        if(isset($param['area_name']) && !empty($param['area_name'])){
            $query->where('a.area_name','like','%'.$param['area_name'].'%'); 
        }
        if(!empty($param['location_id'])){
            $query->where('a.location_id',$param['location_id']);
        }
        $total_count = $query->count();
        if(isset($param['limit']) && isset($param['start'])){
            $query->limit($param['limit'])->offset($param['start']);
        }
        $query->orderBy('a.area_name','asc');
        $result = $query->get();
        if($total_count > 0){
            return array('total_count'=>$total_count,'data'=>$result);
        }else{
            return array('total_count'=>0,'data'=>[]);
        }
    }
}
