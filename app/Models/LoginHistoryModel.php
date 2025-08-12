<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginHistoryModel extends Model
{
    use HasFactory;

    protected $table = 'login_history';

    protected $fillable = [
        'id','user_id', 'login_date_time', 'ip_address', 'device_details','browser_details', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'];

    public function getSaveData() {
        return array('id','user_id', 'login_date_time','ip_address', 'device_details','browser_details', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at');
    }

    public function saveData($post) {
        $saveFields = $this->getSaveData();
        $finalData = new LoginHistoryModel;
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
            return array('id' => $id, 'status' => 'success', 'message' => "Login History Data saved!");
        } else {
            if ($this->getSingleData($id)) {
                $finalData['updated_at'] = date("Y-m-d H:i:s");
                $finalData['updated_by'] = Session::get('id');
                $finalData->exists = true;
                $finalData->id = $id;
                $finalData->save();
                return array('id' => $id, 'status' => 'success', 'message' => "Login History Data updated!");
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

    static function getAllLoginHistoryDetails($param = []){
       $query = DB::table('login_history as c');
       $query->join('users as u','c.created_by','=','u.id');
       $query->join('users as us','c.user_id','=','us.id');
       $query->select(DB::raw("
        c.id,
        c.user_id ,
        c.login_date_time,
        c.ip_address,
        c.device_details,
        c.browser_details,
        c.status,
        u.full_name as created_by,
        date_format(c.created_at,'%d-%m-%Y') as created_at, c.created_by"));
        if(isset($param['status']) && (in_array($param['status'],[0,1]))){
            $query->where('c.status',$param['status']);
        }
        if(!empty($param['user_id'])){
            $query->where('c.user_id',$param['user_id']);
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
