<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\AccessPermissionModel;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Jenssegers\Agent\Agent;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
     use HasApiTokens, Notifiable , HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

     protected $table = 'users';
     protected $modulesTable = 'modules';
     protected $submodulesTable = 'submodules';
     protected $accesspermissionsTable = 'access_permission';

    
    protected $fillable = [
        'id', 'full_name', 'email_id', 'phone_1','gender','email_verified_at', 'password', 'role_id', 'status', 
        'remember_token', 'created_by', 'created_at', 'updated_by', 'updated_at'
      ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getSaveData() {
        return array(
                   'id', 'full_name', 'email_id', 'phone_1','gender','email_verified_at', 'password', 'role_id', 'status', 
        'remember_token', 'created_by', 'created_at', 'updated_by', 'updated_at'
        );
    }

    public function saveData($post) {
        $saveFields = $this->getSaveData();
        $finalData = new User;
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
            $finalData['password'] = Hash::make(12345678);
            $finalData['updated_at'] = null;
            $finalData->save();
            $id = $finalData->id;
            return array('id' => $id, 'status' => 'success', 'message' => "User data saved!");
        } else {
            if ($this->getSingleData($id)) {
                $finalData['updated_at'] = date("Y-m-d H:i:s");
                $finalData->exists = true;
                $finalData->id = $id;
                $finalData->save();
                return array('id' => $id, 'status' => 'success', 'message' => "User data updated!");
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

    function checkLoginDetails($data){
        $result = DB::table($this->table)
            ->where('email_id', $data['email_id'])
            ->where('status', $data['status'])
            ->first();    
        if (!$result || !Hash::check($data['password'], $result->password)) {
            return ['status' => 'warning', 'message' => 'Email or password incorrect!'];
        }
        $access_permission = AccessPermissionModel::getSubModuleWithPermission($result->role_id);
        $array[0] = $result;
        $array[1] = $access_permission;
        
        $returnData = array('status' => 'success', 'data' => $array);
        return $returnData;
    }


    static function details($param = []){
        $query = DB::table('users as u');
        $query->leftjoin('roles as ur','u.role_id','=','ur.id');
        $query->leftjoin('users as u1','u.created_by','=','u1.id');
        $query->leftjoin('users as u2','u2.updated_by','=','u2.id');
        $query->select(DB::raw("u.id,
        ur.role_name,
        u.full_name,
        u.email_id ,
        u.password,
        u.status,
        date_format(u.created_at,'%d-%m-%Y') as created_at,
        ifnull(u1.full_name,'') as created_by,
        ifnull(date_format(u1.created_at,'%d-%m-%Y %h:%m %p'),'') as created_at,
        ifnull(u2.full_name,'') as updated_by,
        ifnull(date_format(u2.updated_at,'%d-%m-%Y %h:%m %p'),'') as updated_at
        "));

        if(isset($param['status']) && (in_array($param['status'],[0,1]))){
            $query->where('u.status',$param['status']);
        }
        if(isset($param['full_name']) && !empty($param['full_name'])){
             $query->where('full_name','like','%'.$param['full_name'].'%');
        }
        if(isset($param['email_id']) && !empty($param['email_id'])){
             $query->where('email_id','like','%'.$param['email_id'].'%');
        }
        if(!empty($param['role_id'])){
            $query->where('u.role_id',$param['role_id']);
        }
         $total_count = $query->count();
         if(isset($param['limit']) && isset($param['offset'])){
             $query->limit($param['limit'])->offset($param['offset']);
         }
         $query->orderBy('u.id','desc');
         $result = $query->get();
         if($total_count > 0){
             return array('total_count'=>$total_count,'data'=>$result);
         }else{
             return array('total_count'=>0,'data'=>[]);
         }
    }

    static function saveUserBrowserDetails($user_id){
        $last_login_at = now();
        $ip_address = request()->ip();  
        $agent = new Agent();
        $browser = $agent->browser();
        $platform = $agent->platform();
        DB::Insert("insert into user_login_details (user_id,login_date_time,ip_address,browser,platform) 
                        values($user_id,'$last_login_at','$ip_address','$browser','$platform')");
    }
}
