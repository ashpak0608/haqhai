<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\UserRoleModel;
use App\Models\SubModuleModel;
use App\Models\CommonModel;
use App\Models\AccessPermissionModel;
use App\validations\AccessPermissionValidation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class UserPageAccessController extends Controller {

    function index() {
        try{
            $data['title'] = "User Page Access || Ajakin";
            $param=['start' => 0 , 'limit' => 10];
            $lists = AccessPermissionModel::getAccessoriesDetails($param);
            $data['total_count'] = $lists['total_count'];
            $data['lists'] = array();
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
            }
            $data['roles'] = CommonModel::getSingle('users_roles', ['status' => 0]);
            return view('user_page_access.index',$data);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function getFiltering(Request $request) {
        try{
            $data = array();
            $param = array();
            $param['start'] = $request->start;
            $param['limit'] = $request->limit;
            $param['role_id'] = $request->role_id;
            $lists = AccessPermissionModel::getAccessoriesDetails($param);
            $data['total_count'] = $lists['total_count'];
            $data['lists'] = array();
            $data['message'] = "No record found!";
            if($lists['total_count'] > 0){
                $count = count($lists['data'])+ $request->start;
                $data['lists'] = $lists['data'];
                $data['status'] = 'success';
                $data['message'] = "Showing ".++$request->start." to ". $count ." of ".$lists['total_count']." records.";
            }
            return json_encode($data);
        }catch(Throwable $e){         
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function add(Request $request , $id=null) { 
        try{
            $data['title'] = "User Page Access - Add || Ajakin";
            $data['accessPermissions'] = array();
            $data['singleData'] = array();
            if($id != null){
                $data['accessPermissions'] = CommonModel::getSingle('access_permission', ['role_id' => $id]);
                $data['singleData'] = $data['accessPermissions'][0]->role_id;
            }
            $param=['status' => 0];
            $data['subModules'] = SubModuleModel::getAllSubModuleDetails($param);
            $data['roles'] = CommonModel::getSingle('users_roles', ['status' => 0]);
            return view('user_page_access.add',$data);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function view($id) {
        $data['title'] = "User Page Access - View || Ajakin";
        return view('user_page_access.view',$data);
    }

    function save(Request $request) {
        try{
            $returnData = array();
            $id = $request['id'];
            $objAccessPermissionValidation = new AccessPermissionValidation();
            $validationResult = $objAccessPermissionValidation->validate($request->all());

            if ($validationResult !== null) {
                return json_encode($validationResult);
            }
            
            $sub_module_id = $request['sub_module_id'];
            $role_id = $request['role_id'];
            $is_insert = $request['is_insert'] ?? [];
            $is_update = $request['is_update'] ?? [];
            $is_view = $request['is_view'] ?? [];
            $is_delete = $request['is_delete'] ?? [];

            $count = count($sub_module_id);
            if($count){
                DB::table('access_permission')->where('role_id', $role_id)->delete();
                for($i=0;$i<$count;$i++){
                    $current_sub_module_id = $sub_module_id[$i];

                    $data = [
                        'role_id' => $role_id,
                        'submodule_id' => $current_sub_module_id,
                        'is_insert' => collect($is_insert)->contains(fn($val) => explode('_', $val)[1] == $current_sub_module_id) ? 1 : 0,
                        'is_update' => collect($is_update)->contains(fn($val) => explode('_', $val)[1] == $current_sub_module_id) ? 1 : 0,
                        'is_view' => collect($is_view)->contains(fn($val) => explode('_', $val)[1] == $current_sub_module_id) ? 1 : 0,
                        'is_delete' => collect($is_delete)->contains(fn($val) => explode('_', $val)[1] == $current_sub_module_id) ? 1 : 0,
                        'status' => 0
                    ];
                    $objRoleModel = new AccessPermissionModel;
                    $returnData = $objRoleModel->saveData($data);
                }
            }
            if (count($returnData) <= 0) {
                $returnData = array('status' => 'error', 'message' => 'Error in data insertion');
            }
            return json_encode($returnData);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

     function updateStatus($status, $id){
        try{
            $id = (int) $id;
            $status = (int)$status === 0 ? 1 : 0;
            DB::table('access_permission')->where('role_id', $id)->update(['status' => $status]);
            $returnData = array('status' => 'success', 'message' => 'Status updated successfully');
            return json_encode($returnData);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    } function dataDownload(Request $request) {}

}