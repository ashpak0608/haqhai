<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\DistrictMasterModel;
use App\validations\DistrictMasterValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class DistrictController extends Controller {

    protected $table = 'district_master';

    function index() {
       try{
            $data['title'] = "District || Ajakin";
            $data['states'] = CommonModel::getSingle('state_master', ['status' => 0]);
            $param=array(
                'start' => 0,
                'limit' => 10,
            );
            $lists = DistrictMasterModel::getAllDistrictDetails($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('district.index',$data);
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
            $param['state_id'] = $request->state_id;
            $param['district_name'] = $request->district_name;
            $param['status'] = $request->status;
            $objDistrictMasterModel = new DistrictMasterModel;
            $lists = $objDistrictMasterModel->getAllDistrictDetails($param);
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
            $data['title'] = "District - Add || Ajakin";
            if($id != null) {
                $data['id'] = $id;
                $objDistrictMasterModel = new DistrictMasterModel();
                $data['singleData'] = $objDistrictMasterModel->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }
            $data['states'] = CommonModel::getSingle('state_master', ['status' => 0]);
            return view('district.add',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function view($id) {
        try{
            $data['title'] = "District - View || Ajakin";
            $param = array('id' => $id);
            $objDistrictMasterModel = new DistrictMasterModel();
            $viewLists = DistrictMasterModel::getAllDistrictDetails($param);
            $data['views'] = $viewLists['data'][0];
            return view('district.view',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function save(Request $request) {
        try{
            $returnData = array();
            $DistrictMasterValidation = new DistrictMasterValidation();
            $validationResult = $DistrictMasterValidation->validate($request->all());
            if ($validationResult !== null) {
                return json_encode($validationResult);
            }
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "state_id" => $request->state_id,
                "district_name" => $request->district_name,
            ];
            $uniqueCount = $objCommon->checkMultiUnique($this->table,$uniqueFieldValue,$request["id"]);
            if ($uniqueCount > 0) {
                $returnData = ["status" => "exist","message" => "District and State already exists!","unique_field" => $uniqueFieldValue];
                return json_encode($returnData);
            }
            $objDistrictMasterModel = new DistrictMasterModel();
            $post = $request->all();
            $returnData = $objDistrictMasterModel->saveData($post);
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
            $objDistrictMasterModel = new DistrictMasterModel;
            $returnData = $objDistrictMasterModel->saveData($data);
            return json_encode($returnData);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    } 
    function dataDownload(Request $request) {}
}