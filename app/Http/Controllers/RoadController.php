<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\RoadModel;
use App\validations\RoadMasterValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class RoadController extends Controller {

    protected $table = 'road';

    function index() {
       try{
            $data['title'] = "Road || HAQHAI";
            $data['cities'] = CommonModel::getSingle('cities', ['status' => 0]);
            $data['wards'] = CommonModel::getSingle('wards', ['status' => 0]);
            $param=array(
                'start' => 0,
                'limit' => 10,
            );
            $lists = RoadModel::getAllRoadDetails($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('road.index',$data);
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
            $param['city_id'] = $request->city_id;
            $param['ward_id'] = $request->ward_id;
            $param['road_name'] = $request->road_name;
            $param['status'] = $request->status;
            $objRoadModel = new RoadModel;
            $lists = $objRoadModel->getAllRoadDetails($param);
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
        }catch(\Throwable $e){         
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function add(Request $request , $id=null) { 
        try{
            $data['title'] = "Road - Add || HAQHAI";
            if($id != null) {
                $data['id'] = $id;
                $objRoadModel = new RoadModel();
                $data['singleData'] = $objRoadModel->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }
            $data['cities'] = CommonModel::getSingle('cities', ['status' => 0]);
            $data['wards'] = CommonModel::getSingle('wards', ['status' => 0]);
            return view('road.add',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function view($id) {
        try{
            $data['title'] = "Road - View || HAQHAI";
            $param = array('id' => $id);
            $objRoadModel = new RoadModel();
            $viewLists = RoadModel::getAllRoadDetails($param);
            $data['views'] = $viewLists['data'][0];
            return view('road.view',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function save(Request $request) {
        try{
            $returnData = array();
            $RoadMasterValidation = new RoadMasterValidation();
            $validationResult = $RoadMasterValidation->validate($request->all());
            if ($validationResult !== null) {
                return json_encode($validationResult);
            }
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "city_id" => $request->city_id,
                "ward_id" => $request->ward_id,
                "road_name" => $request->road_name,
            ];
            $uniqueCount = $objCommon->checkMultiUnique($this->table,$uniqueFieldValue,$request["id"]);
            if ($uniqueCount > 0) {
                $returnData = ["status" => "exist","message" => "Road already exists in this ward and city!","unique_field" => $uniqueFieldValue];
                return json_encode($returnData);
            }
            $objRoadModel = new RoadModel();
            $post = $request->all();
            $returnData = $objRoadModel->saveData($post);
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
            $objRoadModel = new RoadModel;
            $returnData = $objRoadModel->saveData($data);
            return json_encode($returnData);
        }catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    } 
    
    function dataDownload(Request $request) {
        // Export functionality can be implemented here
    }
}