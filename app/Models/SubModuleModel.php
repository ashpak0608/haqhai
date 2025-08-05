<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubModuleModel extends Model
{
    use HasFactory;

    protected $table = 'submodules';

    protected $fillable = [
        'id', 'module_id', 'sub_module_name','sub_module_short_name','controller_name','sequence','status', 'created_by', 'created_at', 'updated_by', 'updated_at'
    ];

    public function getSaveData() {
        return array(
            'id', 'module_id', 'sub_module_name','sub_module_short_name','controller_name','sequence','status', 'created_by', 'created_at', 'updated_by', 'updated_at'
        );
    }  

    public function saveData($post) {
        $saveFields = $this->getSaveData();
        $finalData = new SubModuleModel;
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
            return array('id' => $id, 'status' => 'success', 'message' => "Sub Module data saved!");
        } else {
            if ($this->getSingleData($id)) {
                $finalData['updated_at'] = date("Y-m-d H:i:s");
                $finalData->exists = true;
                $finalData->id = $id;
                $finalData->save();
                return array('id' => $id, 'status' => 'success', 'message' => "Sub Module data updated!");
            } else {
                return false;
            }
        }
    }

    public function getSingleData($id) {
        $id = (int) $id;
        $result = DB::select("SELECT b.* FROM " . $this->table . " as b WHERE b.id=$id");
        foreach ($result as $data) {
            return json_decode(json_encode($data), True);
        }

        return false;
    }

    static function getAllSubModuleDetails($param = []){
       $query = DB::table('submodules as s');
       $query->select(DB::raw("s.id, s.sub_module_name"));

        if(isset($param['status']) && (in_array($param['status'],[0,1]))){
           $query->where('s.status',$param['status']);
        }

        $total_count = $query->count();
        if(isset($param['limit']) && isset($param['offset'])){
            $query->limit($param['limit'])->offset($param['offset']);
        }

        $query->orderBy('s.sub_module_name','ASC');
        $result = $query->get();
        return $result;
    }

    static function getSubModuleLists($param = []){
       $query = DB::table('submodules as s');
       $query->join('modules as m','s.module_id','=','m.id');
       $query->leftjoin('users as u','s.created_by','=','u.id');
       $query->leftjoin('users as u1','s.updated_by','=','u1.id');
       $query->select(DB::raw("
        s.id,
        m.module_name,
        s.sub_module_name,
        s.sub_module_short_name,
        s.controller_name,
        s.sequence,
        s.status,
        ifnull(u.user_name,'') as created_by,
        ifnull(date_format(s.created_at,'%d-%m-%Y %h:%m %p'),'') as created_at,
        ifnull(u1.user_name,'') as updated_by,
        ifnull(date_format(s.updated_at,'%d-%m-%Y %h:%m %p'),'') as updated_at
       "));
        if(isset($param['status']) && (in_array($param['status'],[0,1]))){
            $query->where('s.status',$param['status']);
        }
        if(!empty($param['id'])){
            $query->where('s.id',$param['id']);
        }
        if(isset($param['sub_module_short_name']) && !empty($param['sub_module_short_name'])){
            $query->where('s.sub_module_short_name','like','%'.$param['sub_module_short_name'].'%'); 
        }
        if(!empty($param['module_id'])){
            $query->where('s.module_id',$param['module_id']);
        }
        $total_count = $query->count();
        if(isset($param['limit']) && isset($param['start'])){
            $query->limit($param['limit'])->offset($param['start']);
        }
        $query->orderBy('s.sub_module_short_name', 'asc')
            ->orderBy('s.sequence', 'asc');
        $result = $query->get();
        if($total_count > 0){
            return array('total_count'=>$total_count,'data'=>$result);
        }else{
            return array('total_count'=>0,'data'=>[]);
        }
    }
}