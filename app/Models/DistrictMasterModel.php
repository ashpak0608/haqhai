<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DistrictMasterModel extends Model
{
    use HasFactory;

    protected $table = 'district_master';

    protected $fillable = [
        'id','state_id', 'district_name', 'display_in_home_page','status', 'created_by', 'created_at', 'updated_by', 'updated_at'];

    public function getSaveData() {
        return array('id','state_id', 'district_name', 'display_in_home_page','status', 'created_by', 'created_at', 'updated_by', 'updated_at');
    }

    public function saveData($post) {
        $saveFields = $this->getSaveData();
        $finalData = new DistrictMasterModel;
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
            return array('id' => $id, 'status' => 'success', 'message' => "District Data saved!");
        } else {
            if ($this->getSingleData($id)) {
                $finalData['updated_at'] = date("Y-m-d H:i:s");
                $finalData['updated_by'] = Session::get('id');
                $finalData->exists = true;
                $finalData->id = $id;
                $finalData->save();
                return array('id' => $id, 'status' => 'success', 'message' => "District Data updated!");
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

    static function getAllDistrictDetails($param = []){
       $query = DB::table('district_master as c');
       $query->leftjoin('users as u','c.created_by','=','u.id');
       $query->leftjoin('users as u1','c.updated_by','=','u1.id');
       $query->join('state_master as s','c.state_id','=','s.id');
       $query->select(DB::raw("
        c.id,
        c.district_name,
        c.state_id,
        c.status,
        s.state_name,
        ifnull(u.user_name,'') as created_by,
        ifnull(date_format(c.created_at,'%d-%m-%Y %h:%m %p'),'') as created_at,
        ifnull(u1.user_name,'') as updated_by,
        ifnull(date_format(c.updated_at,'%d-%m-%Y %h:%m %p'),'') as updated_at"));
        if(isset($param['status']) && (in_array($param['status'],[0,1]))){
            $query->where('c.status',$param['status']);
        }
        if(isset($param['display_in_home_page']) && (in_array($param['display_in_home_page'],[1]))){
            $query->where('c.display_in_home_page',$param['display_in_home_page']);
        }
        if(!empty($param['id'])){
            $query->where('c.id',$param['id']);
        }
        if(isset($param['district_name']) && !empty($param['district_name'])){
            $query->where('c.district_name','like','%'.$param['district_name'].'%'); 
        }
        if(!empty($param['state_id'])){
            $query->where('c.state_id',$param['state_id']);
        }
        $total_count = $query->count();
        if(isset($param['limit']) && isset($param['start'])){
            $query->limit($param['limit'])->offset($param['start']);
        }
        $query->orderBy('c.district_name','asc');
        $result = $query->get();
        if($total_count > 0){
            return array('total_count'=>$total_count,'data'=>$result);
        }else{
            return array('total_count'=>0,'data'=>[]);
        }
    }
}
