<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\PinLocationMasterModel;
use App\Models\AreaModel;
use App\validations\AreaMasterValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class AreaController extends Controller {

    protected $table = 'areas';
    
    function index() {
        try{
            $data['title'] = "Area || HAQHAI";
            $data['districts'] = CommonModel::getSingle('district', ['status' => 0]);
            $param=array(
                'start' => 0,
                'limit' => 10,
            );
            $lists = AreaModel::details($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('area.index',$data);
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
            $param['area_name'] = $request->area_name;
            $param['location_id'] = $request->location_id;
            $param['district_id'] = $request->district_id;
            $param['status'] = $request->status;
            $objAreaModel = new AreaModel;
            $lists = $objAreaModel->details($param);
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
            $data['title'] = "Area - Add || HAQHAI";
            if($id != null) {
                $data['id'] = $id;
                $objAreaModel = new AreaModel();
                $data['singleData'] = $objAreaModel->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }
            $data['districts'] = CommonModel::getSingle('district', ['status' => 0]);
            return view('area.add',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function view($id) {
        try{
            $data['title'] = "Area - View || HAQHAI";
            $param = array('id' => $id);
            $viewLists = AreaModel::details($param);
            $data['views'] = $viewLists['data'][0];
            return view('area.view',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function save(Request $request) {
        try{
            $returnData = array();
            $AreaMasterValidation = new AreaMasterValidation();
            $validationResult = $AreaMasterValidation->validate($request->all());
            if ($validationResult !== null) {
                return json_encode($validationResult);
            }
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "area_name" => $request->area_name,
                "district_id" => $request->district_id,
                "area_name" => $request->area_name,
            ];
            $uniqueCount = $objCommon->checkMultiUnique($this->table,$uniqueFieldValue,$request["id"]);
            if ($uniqueCount > 0) {
                $returnData = ["status" => "exist","message" => "Location Name , District  and Area  already exists!","unique_field" => $uniqueFieldValue];
                return json_encode($returnData);
            }
            $objAreaModel = new AreaModel();
            $post = $request->all();
            $returnData = $objAreaModel->saveData($post);
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
            $objAreaModel = new AreaModel;
            $returnData = $objAreaModel->saveData($data);
            return json_encode($returnData);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    } 
    function dataDownload(Request $request) {}

}