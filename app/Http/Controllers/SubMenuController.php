<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\SubModuleModel;
use App\validations\SubModuleMasterValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class SubMenuController extends Controller {

    protected $table = 'submodules';

    function index() {
        try{
            $data['title'] = "Sub Menu || HAQHAI";
            $data['modules'] = CommonModel::getSingle('modules', ['status' => 0]);
            $param=array(
                'start' => 0,
                'limit' => 10,
            );
            $lists = SubModuleModel::getSubModuleLists($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('sub_menu.index',$data);
        }
        catch(\Throwable $e){
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
            $param['module_id'] = $request->module_id;
            $param['sub_module_short_name'] = $request->sub_module_short_name;
            $param['status'] = $request->status;
            $lists = SubModuleModel::getSubModuleLists($param);
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
            $data['title'] = "Sub Menu - Add || HAQHAI";
            if($id != null) {
                $data['id'] = $id;
                $objSubModuleModel = new SubModuleModel();
                $data['singleData'] = $objSubModuleModel->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }
            $data['modules'] = CommonModel::getSingle('modules', ['status' => 0]);
            return view('sub_menu.add',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function view($id) {
         try{
            $data['title'] = "Sib Menu - View || HAQHAI";
            $param = array('id' => $id);
            $viewLists = SubModuleModel::getSubModuleLists($param);
            $data['views'] = $viewLists['data'][0];
            return view('sub_menu.view',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function save(Request $request) {
        try{
            $returnData = array();
            $SubModuleMasterValidation = new SubModuleMasterValidation();
            $validationResult = $SubModuleMasterValidation->validate($request->all());
            if ($validationResult !== null) {
                return json_encode($validationResult);
            }
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "module_id" => $request->module_id,
                "sub_module_short_name" => $request->sub_module_short_name,
            ];
            $uniqueCount = $objCommon->checkMultiUnique($this->table,$uniqueFieldValue,$request["id"]);
            if ($uniqueCount > 0) {
                $returnData = ["status" => "exist","message" => "Main Menu and Sub Menu already exists!","unique_field" => $uniqueFieldValue];
                return json_encode($returnData);
            }
            $objSubModuleModel = new SubModuleModel();
            $post = $request->all();
            $post['sub_module_name'] = $request->sub_module_short_name;
            $post['sub_module_short_name'] = $request->sub_module_short_name;
            $returnData = $objSubModuleModel->saveData($post);
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
            $objSubModuleModel = new SubModuleModel;
            $returnData = $objSubModuleModel->saveData($data);
            return json_encode($returnData);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    } function dataDownload(Request $request) {}

}