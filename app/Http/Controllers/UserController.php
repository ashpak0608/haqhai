<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CountryMasterModel;
use App\validations\UserDetailValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

class UserController extends Controller {

    protected $table = 'users';

    function index() {
        try{
            $data['title'] = "User || HAQHAI";
            $param=array(
                    'start' => 0,
                    'limit' => 10,
                );
            $lists = User::details($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('user.index',$data);
        }catch(\Throwable $e){
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
            $param['full_name'] = $request->full_name;
            $param['email_id'] = $request->email_id;
            $param['phone1'] = $request->phone1;
            $objUser = new User;
            $lists = $objUser->details($param);
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
            $data['title'] = "User - Add || HAQHAI";
            if($id != null) {
                $data['id'] = $id;
                $objUser = new User();
                $data['singleData'] = $objUser->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }
            $data['roles'] = CommonModel::getSingle('roles', ['status' => 0]);
            return view('user.add',$data);
        }catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function view($id=null) {
        try{
            $data['title'] = "User - View || HAQHAI";
            $param = array('id' => $id);
            $viewLists = User::details($param);
            $data['views'] = $viewLists['data'][0];
            return view('user.view',$data);
        }catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function save(Request $request) {
        try{
            $returnData = array();
            $UserDetailValidation = new UserDetailValidation();
            $validationResult = $UserDetailValidation->validate($request->all());
            if ($validationResult !== null) {
                return json_encode($validationResult);
            }
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "email_id" => $request->email_id,
            ];
            $uniqueCount = $objCommon->checkMultiUnique($this->table,$uniqueFieldValue,$request["id"]);
            if ($uniqueCount > 0) {
                $returnData = ["status" => "exist","message" => "Email Id already exists!","unique_field" => $uniqueFieldValue];
                return json_encode($returnData);
            }
            $uniqueFieldValue = [
                "phone_1" => $request->phone_1,
            ];
            $uniqueCount = $objCommon->checkMultiUnique($this->table,$uniqueFieldValue,$request["id"]);
            if ($uniqueCount > 0) {
                $returnData = ["status" => "exist","message" => "Phone 1 Id already exists!","unique_field" => $uniqueFieldValue];
                return json_encode($returnData);
            }
            $objUser = new User();
            $post = $request->all();
            $returnData = $objUser->saveData($post);
            return json_encode($returnData);
        }catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

     function updateStatus($status, $id){
        try{
            $id = (int) $id;
            $status = (int)$status === 0 ? 1 : 0;
            $data = array('status' => $status , 'id' => $id);
            $objUser = new User;
            $returnData = $objUser->saveData($data);
            return json_encode($returnData);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    } function dataDownload(Request $request) {}

}