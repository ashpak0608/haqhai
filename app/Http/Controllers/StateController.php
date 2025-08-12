<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\StateMasterModel;
use App\validations\StateMasterValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class StateController extends Controller {
    
    protected $table = 'states';

    function index() {
        try{
            $data['title'] = "State || HAQHAI";
            $param=array(
                'start' => 0,
                'limit' => 10,
            );
            $lists = StateMasterModel::getAllStateMasterDetails($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('state.index',$data);
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
            $param['state_name'] = $request->state_name;
            $param['status'] = $request->status;
            $objStateMasterModel = new StateMasterModel;
            $lists = $objStateMasterModel->getAllStateMasterDetails($param);
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
            $data['title'] = "State - Add || HAQHAI";
            if($id != null) {
                $data['id'] = $id;
                $objStateMasterModel = new StateMasterModel();
                $data['singleData'] = $objStateMasterModel->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }
            return view('state.add',$data);
        }catch(\Throwable $e){
                $returnData = array('status' => 'warning', 'message' => $e->getMessage());
                return json_encode($returnData);
            }
    }

    function view($id) {
        try{
            $data['title'] = "State - View || HAQHAI";
            $param = array('id' => $id);
            $viewLists = StateMasterModel::getAllStateMasterDetails($param);
            $data['views'] = $viewLists['data'][0];
            return view('state.view',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function save(Request $request) {
        try{
            $returnData = array();
            $StateMasterValidation = new StateMasterValidation();
            $validationResult = $StateMasterValidation->validate($request->all());
            if ($validationResult !== null) {
                return json_encode($validationResult);
            }
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "state_name" => $request->state_name,
            ];
            $uniqueCount = $objCommon->checkMultiUnique($this->table,$uniqueFieldValue,$request["id"]);
            if ($uniqueCount > 0) {
                $returnData = ["status" => "exist","message" => "State already exists!","unique_field" => $uniqueFieldValue];
                return json_encode($returnData);
            }
            $objStateMasterModel = new StateMasterModel();
            $post = $request->all();
            $returnData = $objStateMasterModel->saveData($post);
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
            $objStateMasterModel = new StateMasterModel;
            $returnData = $objStateMasterModel->saveData($data);
            return json_encode($returnData);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    } function dataDownload(Request $request) {}

}