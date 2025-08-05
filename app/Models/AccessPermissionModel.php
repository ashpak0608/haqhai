<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessPermissionModel extends Model
{
    use HasFactory;

    protected $table = 'access_permission';

    protected $fillable = [
        'id', 'role_id','submodule_id','is_insert','is_update','is_view','is_delete', 'status', 'created_by', 'created_at', 
        'updated_by', 'updated_at'
    ];

    public function getSaveData() {
        return array(
            'id', 'role_id','submodule_id','is_insert','is_update','is_view','is_delete', 'status', 'created_by', 'created_at', 
            'updated_by', 'updated_at'
        );
    }  

    public function saveData($post) {
        $saveFields = $this->getSaveData();
        $finalData = new AccessPermissionModel;
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
            return array('id' => $id, 'status' => 'success', 'message' => "Access Permission Data saved!");
        } else {
            if ($this->getSingleData($id)) {
                $finalData['updated_at'] = date("Y-m-d H:i:s");
                $finalData['updated_by'] = Session::get('id');
                $finalData->exists = true;
                $finalData->id = $id;
                $finalData->save();
                return array('id' => $id, 'status' => 'success', 'message' => "Access Permission Data updated!");
            } else {
                return false;
            }
        }
    }

    public function getSingleData($id) {
        $id = (int) $id;
        $result = DB::select("SELECT a.* FROM " . $this->table . " as a WHERE a.role_id=$id");
        foreach ($result as $data) {
            return json_decode(json_encode($data), True);
        }

        return false;
    }

    public static function getAllAccessPermissionDetails($param = []) {
        $query = DB::table('access_permission as a')
            ->leftJoin('users_roles as r', 'a.role_id', '=', 'r.id')
            ->select('a.role_id', 'r.role_name', 'r.status')
            ->distinct()
            ->orderBy('r.role_name', 'ASC')
            ->groupBy('a.role_id', 'r.role_name','r.status');
        return $query->get();
    }

    public static function getSubModuleWithPermission($role_id){
        $query = DB::table('access_permission as a');
        $query->join("submodules as s",'a.submodule_id','=','s.id');
        $query->leftjoin("modules as m",'s.module_id','=','m.id');
        $query->select(DB::raw("
            role_id,controller_name,is_insert,is_update,is_view,is_delete,module_name,
            submodule_id,sub_module_name,s.module_id,m.id,m.icon 
        "));
        $query->whereRaw("
                NOT (is_insert = 0 AND is_update = 0 AND 
                is_view = 0 AND is_delete = 0)
            ");
        $query->where('role_id',$role_id);
        $query->where('s.status',0);
        $query->orderBy('s.sequence','asc');
        $result = $query->get();
        return $result;
    }

    static function getAccessoriesDetails($param = []){
        $query = DB::table('access_permission as l');
        $query->join('users_roles as r','l.role_id','=','r.id');
        $query->leftjoin('users as u','l.created_by','=','u.id');
        $query->leftjoin('users as u1','l.updated_by','=','u1.id');
        $query->select(DB::raw("l.id, r.role_name, l.status,l.role_id,
         ifnull(u.user_name,'') as created_by,
         ifnull(date_format(l.created_at,'%d-%m-%Y %h:%m %p'),'') as created_at,
         ifnull(u1.user_name,'') as updated_by,
         ifnull(date_format(l.updated_at,'%d-%m-%Y %h:%m %p'),'') as updated_at,
         l.created_by"));
         
         if(isset($param['status']) && (in_array($param['status'],[0,1]))){
             $query->where('l.status',$param['status']);
         }
         if(isset($param['id']) && !empty($param['id'])){
             $query->where('l.id',$param['id']); 
         }
         if(isset($param['role_name']) && !empty($param['role_name'])){
             $query->where('r.role_name','like','%'.$param['role_name'].'%'); 
         }
         $total_count = $query->count();
         
         if(isset($param['limit']) && isset($param['offset'])){
             $query->limit($param['limit'])->offset($param['offset']);
         }
         
         $query->orderBy('l.id','desc');
         $query->groupBy('l.role_id');
         $result = $query->get();
         if($total_count > 0){
             return array('total_count'=>$total_count,'data'=>$result);
         }else{
             return array('total_count'=>0,'data'=>[]);
         }
     }
}