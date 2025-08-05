<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserLoginInfoModel extends Model
{
    use HasFactory;

    protected $table = 'user_login_details';
    public $timestamps = false;

    protected $fillable = [
        'id', 'user_id','login_date_time', 'ip_address', 'browser', 'platform'];

    public function getSaveData() {
        return array(
            'id', 'user_id','login_date_time', 'ip_address', 'browser', 'platform'    
        );
    }

    public function saveData($post) {
        $saveFields = $this->getSaveData();
        $finalData = new UserLoginInfoModel;
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
            $finalData->save();
            $id = $finalData->id;
            return array('id' => $id, 'status' => 'success', 'message' => "User Info Data saved!");
        } else {
            if ($this->getSingleData($id)) {
                $finalData->exists = true;
                $finalData->id = $id;
                $finalData->save();
                return array('id' => $id, 'status' => 'success', 'message' => "User Info Data updated!");
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

    static function details($param = []){
       $query = DB::table('user_login_details as c');
       $query->leftjoin('users as u','c.user_id','=','u.id');
       $query->select(DB::raw("
        c.id,
        u.user_name,
        ip_address,
        browser,
        platform,
        ifnull(date_format(c.login_date_time,'%d-%m-%Y %h:%m %p'),'') as login_date_time"));
        
        if(isset($param['id']) && !empty($param['id'])){
            $query->where('c.id',$param['id']); 
        }
        if(isset($param['user_id']) && !empty($param['user_id'])){
            $query->where('c.user_id',$param['user_id']); 
        }
        $total_count = $query->count();
        if(isset($param['limit']) && isset($param['start'])){
            $query->limit($param['limit'])->offset($param['start']);
        }
        $query->orderBy('u.id','DESC');
        $result = $query->get();
        if($total_count > 0){
            return array('total_count'=>$total_count,'data'=>$result);
        }else{
            return array('total_count'=>0,'data'=>[]);
        }
    }

}
