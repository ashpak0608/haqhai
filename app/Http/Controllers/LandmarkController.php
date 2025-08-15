<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\LandmarkMasterModel;
use App\validations\LandmarkMasterValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class LandmarkController extends Controller {

    protected $table = 'landmarks';
    
    function index() {
        try{
            $data['title'] = "Landmark || HAQHAI";
            $data['areas'] = CommonModel::getSingle('areas', ['status' => 0]);
            $param=array(
                'start' => 0,
                'limit' => 10,
            );
            $lists = LandmarkMasterModel::getAllLandmarkDetails($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('landmark.index',$data);
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
            $param['area_id'] = $request->area_id;
            $param['status'] = $request->status;
            $objLandmarkMasterModel = new LandmarkMasterModel;
            $lists = $objLandmarkMasterModel->getAllLandmarkDetails($param);
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
                $objLandmarkMasterModel = new LandmarkMasterModel();
                $data['singleData'] = $objLandmarkMasterModel->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }
            $data['areas'] = CommonModel::getSingle('areas', ['status' => 0]);
            return view('landmark.add',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function view($id) {
        try{
            $data['title'] = "Landmark - View || HAQHAI";
            $param = array('id' => $id);
            $viewLists = LandmarkMasterModel::getAllLandmarkDetails($param);
            $data['views'] = $viewLists['data'][0];
            return view('landmark.view',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function save(Request $request) {
        try{
            $returnData = array();
            $LandmarkMasterValidation = new LandmarkMasterValidation();
            $validationResult = $LandmarkMasterValidation->validate($request->all());
            if ($validationResult !== null) {
                return json_encode($validationResult);
            }
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "landmark_name" => $request->landmark_name,
                "area_id" => $request->area_id,
            ];
            $uniqueCount = $objCommon->checkMultiUnique($this->table,$uniqueFieldValue,$request["id"]);
            if ($uniqueCount > 0) {
                $returnData = ["status" => "exist","message" => "Landmark Name and Area Name already exists!","unique_field" => $uniqueFieldValue];
                return json_encode($returnData);
            }
            $objLandmarkMasterModel = new LandmarkMasterModel();
            $post = $request->all();
            $returnData = $objLandmarkMasterModel->saveData($post);
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
            $objLandmarkMasterModel = new LandmarkMasterModel;
            $returnData = $objLandmarkMasterModel->saveData($data);
            return json_encode($returnData);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    } 

    function getLocation(Request $request){
        try{
            return json_encode(CommonModel::getSingle('pin_location_master', ['status' => 0 , 'city_id' => $request->city_id]));
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    
    function dataDownload(Request $request) {}

}