<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\PinLocationMasterModel;
use App\validations\PinLocationMasterValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class LocationController extends Controller {

    protected $table = 'pin_location_master';
    
    function index() {
        try{
            $data['title'] = "Location || HAQHAI";
            $data['districts'] = CommonModel::getSingle('district', ['status' => 0]);
            $param=array(
                'start' => 0,
                'limit' => 10,
            );
            $lists = PinLocationMasterModel::getAllPinLocationDetails($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('location.index',$data);
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
            $param['location_name'] = $request->location_name;
            $param['pincode'] = $request->pincode;
            $param['district_id'] = $request->district_id;
            $param['status'] = $request->status;
            $objPinLocationMasterModel = new PinLocationMasterModel;
            $lists = $objPinLocationMasterModel->getAllPinLocationDetails($param);
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
            $data['title'] = "Location - Add || HAQHAI";
            if($id != null) {
                $data['id'] = $id;
                $objPinLocationMasterModel = new PinLocationMasterModel();
                $data['singleData'] = $objPinLocationMasterModel->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }
            $data['districts'] = CommonModel::getSingle('district', ['status' => 0]);
            return view('location.add',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function view($id) {
        try{
            $data['title'] = "Location - View || HAQHAI";
            $param = array('id' => $id);
            $viewLists = PinLocationMasterModel::getAllPinLocationDetails($param);
            $data['views'] = $viewLists['data'][0];
            return view('location.view',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function save(Request $request) {
        try{
            $returnData = array();
            $PinLocationMasterValidation = new PinLocationMasterValidation();
            $validationResult = $PinLocationMasterValidation->validate($request->all());
            if ($validationResult !== null) {
                return json_encode($validationResult);
            }
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "location_name" => $request->location_name,
                "district_id" => $request->district_id,
                "pincode" => $request->pincode,
            ];
            $uniqueCount = $objCommon->checkMultiUnique($this->table,$uniqueFieldValue,$request["id"]);
            if ($uniqueCount > 0) {
                $returnData = ["status" => "exist","message" => "Location Name , District  and Pincode  already exists!","unique_field" => $uniqueFieldValue];
                return json_encode($returnData);
            }
            $objPinLocationMasterModel = new PinLocationMasterModel();
            $post = $request->all();
            $returnData = $objPinLocationMasterModel->saveData($post);
            return json_encode($returnData);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function updateStatus($status, $id){
        try{
            $id = (int) $id;
            $status = (int)$status === 0 ? 1 : 0;
            $data = array('status' => $status , 'id' => $id);
            $objPinLocationMasterModel = new PinLocationMasterModel;
            $returnData = $objPinLocationMasterModel->saveData($data);
            return json_encode($returnData);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    } 

    function getLocation(Request $request){
        try{
            return json_encode(CommonModel::getSingle('pin_location_master', ['status' => 0 , 'district_id' => $request->district_id]));
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    
    function dataDownload(Request $request) {}

}