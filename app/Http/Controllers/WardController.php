<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\CityModel;
use App\Models\WardModel;
use App\validations\WardMasterValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class WardController extends Controller {

    protected $table = 'wards';

    function index() {
       try{
            $data['title'] = "Ward || HAQHAI";
            $data['cities'] = CommonModel::getSingle('cities', ['status' => 0]);
            $param=array(
                'start' => 0,
                'limit' => 10,
            );
            $lists = WardModel::getAllWardDetails($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('ward.index',$data);
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
            $param['ward_name'] = $request->ward_name;
            $param['status'] = $request->status;
            $objWardModel = new WardModel;
            $lists = $objWardModel->getAllWardDetails($param);
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
            $data['title'] = "Ward - Add || HAQHAI";
            if($id != null) {
                $data['id'] = $id;
                $objWardModel = new WardModel();
                $data['singleData'] = $objWardModel->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }
            $data['districcitiess'] = CommonModel::getSingle('cities', ['status' => 0]);
            return view('ward.add',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function view($id) {
        try{
            $data['title'] = "Ward - View || HAQHAI";
            $param = array('id' => $id);
            $objWardModel = new WardModel();
            $viewLists = $objWardModel::getAllWardDetails($param);
            $data['views'] = $viewLists['data'][0];
            return view('ward.view',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function save(Request $request) {
        try{
            $returnData = array();
            $WardMasterValidation = new WardMasterValidation();
            $validationResult = $WardMasterValidation->validate($request->all());
            if ($validationResult !== null) {
                return json_encode($validationResult);
            }
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "city_id" => $request->city_id,
                "ward_name" => $request->ward_name,
                "ward_number" => $request->ward_number
            ];
            $uniqueCount = $objCommon->checkMultiUnique($this->table,$uniqueFieldValue,$request["id"]);
            if ($uniqueCount > 0) {
                $returnData = ["status" => "exist","message" => "City and Ward already exists!","unique_field" => $uniqueFieldValue];
                return json_encode($returnData);
            }
            $objWardModel = new WardModel();
            $post = $request->all();
            $returnData = $objWardModel->saveData($post);
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
            $objWardModel = new WardModel;
            $returnData = $objWardModel->saveData($data);
            return json_encode($returnData);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    } 
    function dataDownload(Request $request) {}
}