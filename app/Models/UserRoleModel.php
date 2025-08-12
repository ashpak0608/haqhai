<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoleModel extends Model
{
    use HasFactory;

    protected $table = 'users_roles';

    protected $fillable = [
        'id', 'role_name', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'];

    public function getSaveData() {
        return array('id','role_name', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at');
    }  

    public function saveData($post) {
        $saveFields = $this->getSaveData();
        $finalData = new UserRoleModel;
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
            return array('id' => $id, 'status' => 'success', 'message' => "Role Data saved!");
        } else {
            if ($this->getSingleData($id)) {
                $finalData['updated_at'] = date("Y-m-d H:i:s");
                $finalData['updated_by'] = Session::get('id');
                $finalData->exists = true;
                $finalData->id = $id;
                $finalData->save();
                return array('id' => $id, 'status' => 'success', 'message' => "Role Data updated!");
            } else {
                return false;
            }
        }
    }

    public function getSingleData($id) {
        $id = (int) $id;
        $result = DB::select("SELECT l.* FROM " . $this->table . " as l WHERE l.id=$id");
        foreach ($result as $data) {
            return json_decode(json_encode($data), True);
        }

        return false;
    }

    static function getAllRoleDetails($param = []){
       $query = DB::table('users_roles as l');
       $query->leftjoin('users as u','l.created_by','=','u.id');
       $query->leftjoin('users as u1','l.updated_by','=','u1.id');
       $query->select(DB::raw("l.id, l.role_name, l.status,
        ifnull(u.full_name,'') as created_by,
        ifnull(date_format(l.created_at,'%d-%m-%Y %h:%m %p'),'') as created_at,
        ifnull(u1.full_name,'') as updated_by,
        ifnull(date_format(l.updated_at,'%d-%m-%Y %h:%m %p'),'') as updated_at,
        l.created_by"));
        
        if(isset($param['status']) && (in_array($param['status'],[0,1]))){
            $query->where('l.status',$param['status']);
        }
        if(isset($param['id']) && !empty($param['id'])){
            $query->where('l.id',$param['id']); 
        }
        if(isset($param['role_name']) && !empty($param['role_name'])){
            $query->where('l.role_name','like','%'.$param['role_name'].'%'); 
        }
        $total_count = $query->count();
        
        if(isset($param['limit']) && isset($param['offset'])){
            $query->limit($param['limit'])->offset($param['offset']);
        }
        
        $query->orderBy('l.id','desc');
        $result = $query->get();
        if($total_count > 0){
            return array('total_count'=>$total_count,'data'=>$result);
        }else{
            return array('total_count'=>0,'data'=>[]);
        }
    }
}
