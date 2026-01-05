<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoadModel extends Model
{
    use HasFactory;

    protected $table = 'road';

    protected $fillable = [
        'id', 'road_name', 'ward_id', 'city_id', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'
    ];

    public function getSaveData() {
        return array(
            'id', 'road_name', 'ward_id', 'city_id', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'
        );
    }

    public function saveData($post) {
        $saveFields = $this->getSaveData();
        $finalData = new RoadModel;
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
            return array('id' => $id, 'status' => 'success', 'message' => "Road Data saved!");
        } else {
            if ($this->getSingleData($id)) {
                $finalData['updated_at'] = date("Y-m-d H:i:s");
                $finalData['updated_by'] = Session::get('id');
                $finalData->exists = true;
                $finalData->id = $id;
                $finalData->save();
                return array('id' => $id, 'status' => 'success', 'message' => "Road Data updated!");
            } else {
                return false;
            }
        }
    }

    public function getSingleData($id) {
        $id = (int) $id;
        $result = DB::select("SELECT r.* FROM " . $this->table . " as r WHERE r.id=$id");
        foreach ($result as $data) {
            return json_decode(json_encode($data), True);
        }

        return false;
    }

    static function getAllRoadDetails($param = []){
       $query = DB::table('road as r');
       $query->leftjoin('users as u','r.created_by','=','u.id');
       $query->leftjoin('users as u1','r.updated_by','=','u1.id');
       $query->join('wards as w','r.ward_id','=','w.id');
       $query->join('cities as c','r.city_id','=','c.id');
       $query->select(DB::raw("
        r.id,
        r.road_name,
        r.ward_id,
        r.city_id,
        r.status,
        w.ward_name,
        c.city_name,
        ifnull(u.full_name,'') as created_by,
        ifnull(date_format(r.created_at,'%d-%m-%Y %h:%m %p'),'') as created_at,
        ifnull(u1.full_name,'') as updated_by,
        ifnull(date_format(r.updated_at,'%d-%m-%Y %h:%m %p'),'') as updated_at"));
        
        if(isset($param['status']) && (in_array($param['status'],[0,1]))){
            $query->where('r.status',$param['status']);
        }
        if(!empty($param['id'])){
            $query->where('r.id',$param['id']);
        }
        if(isset($param['road_name']) && !empty($param['road_name'])){
            $query->where('r.road_name','like','%'.$param['road_name'].'%'); 
        }
        if(!empty($param['city_id'])){
            $query->where('r.city_id',$param['city_id']);
        }
        if(!empty($param['ward_id'])){
            $query->where('r.ward_id',$param['ward_id']);
        }
        
        $total_count = $query->count();
        if(isset($param['limit']) && isset($param['start'])){
            $query->limit($param['limit'])->offset($param['start']);
        }
        $query->orderBy('r.road_name','asc');
        $result = $query->get();
        
        if($total_count > 0){
            return array('total_count'=>$total_count,'data'=>$result);
        }else{
            return array('total_count'=>0,'data'=>[]);
        }
    }
}