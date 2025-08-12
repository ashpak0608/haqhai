<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\UserRoleModel;
use App\Models\CommonModel;
use App\validations\UserRoleValidation;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class RoleController extends Controller {

    protected $table = 'users_roles';

    function index() {
        try{
            $data['title'] = "Role || HAQHAI";
            $param = array('start' => 0, 'limit' => 10);
            $objUserRoleModel = new UserRoleModel;
            $lists = $objUserRoleModel->getAllRoleDetails($param);
            $data['lists'] = array();
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('role.index',$data);
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
            $param['role_name'] = $request->role_name;
            $objUserRoleModel = new UserRoleModel;
            $lists = $objUserRoleModel->getAllRoleDetails($param);
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
            $data['title'] = "Role - Add || HAQHAI";
            if($id != null) {
                $data['id'] = $id;
                $objUserRoleModel = new UserRoleModel();
                $data['singleData'] = $objUserRoleModel->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }
            return view('role.add',$data);
        }catch(\Throwable $e){
                $returnData = array('status' => 'warning', 'message' => $e->getMessage());
                return json_encode($returnData);
        }
    }

    function view($id) {
        try{
            $data['title'] = "Role - View || HAQHAI";
            $param = array('id' => $id);
            $viewLists = UserRoleModel::getAllRoleDetails($param);
            $data['views'] = $viewLists['data'][0];
            return view('role.view',$data);
        }catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function save(Request $request) {
        try{
            $returnData = array();
            $id = $request['id'];

            $objRoleValidation = new UserRoleValidation();
            $validationResult = $objRoleValidation->validate($request->all());

            if ($validationResult !== null) {
                return json_encode($validationResult);
            }
            $objCommon = new CommonModel;
            $uniqueFieldValue = array(
                'role_name' => $request->role_name
            );
            $uniqueCount = $objCommon->checkMultiUnique($this->table, $uniqueFieldValue, $request['id']);       
            if ($uniqueCount > 0) {
                $returnData = array('status' => 'exist', 'message' => 'Role Name already exists!', 'unique_field' => $uniqueFieldValue);
                return json_encode($returnData);
            }
            $objRoleModel = new UserRoleModel;
            $returnData = $objRoleModel->saveData($request->all());
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
            $data = array('status' => $status , 'id' => $id);
            $objUserRoleModel = new UserRoleModel;
            $returnData = $objUserRoleModel->saveData($data);
            return json_encode($returnData);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    } 
    function dataDownload(Request $request) {}

}