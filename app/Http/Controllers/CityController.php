<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\CityModel;
use App\validations\CityMasterValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class CityController extends Controller {

    protected $table = 'cities';

    function index() {
       try{
            $data['title'] = "City || HAQHAI";
            $data['districts'] = CommonModel::getSingle('district', ['status' => 0]);
            $param=array(
                'start' => 0,
                'limit' => 10,
            );
            $lists = CityModel::getAllCityDetails($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('city.index',$data);
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
            $param['district_id'] = $request->district_id;
            $param['city_name'] = $request->city_name;
            $param['status'] = $request->status;
            $objCityModel = new CityModel;
            $lists = $objCityModel->getAllCityDetails($param);
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
            $data['title'] = "City - Add || HAQHAI";
            if($id != null) {
                $data['id'] = $id;
                $objCityModel = new CityModel();
                $data['singleData'] = $objCityModel->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }
            $data['districts'] = CommonModel::getSingle('district', ['status' => 0]);
            return view('city.add',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function view($id) {
        try{
            $data['title'] = "City - View || HAQHAI";
            $param = array('id' => $id);
            $objCityModel = new CityModel();
            $viewLists = CityModel::getAllCityDetails($param);
            $data['views'] = $viewLists['data'][0];
            return view('city.view',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function save(Request $request) {
        try{
            $returnData = array();
            $CityMasterValidation = new CityMasterValidation();
            $validationResult = $CityMasterValidation->validate($request->all());
            if ($validationResult !== null) {
                return json_encode($validationResult);
            }
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "district_id" => $request->district_id,
                "city_name" => $request->city_name,
            ];
            $uniqueCount = $objCommon->checkMultiUnique($this->table,$uniqueFieldValue,$request["id"]);
            if ($uniqueCount > 0) {
                $returnData = ["status" => "exist","message" => "City and State already exists!","unique_field" => $uniqueFieldValue];
                return json_encode($returnData);
            }
            $objCityModel = new CityModel();
            $post = $request->all();
            $returnData = $objCityModel->saveData($post);
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
            $objCityModel = new CityModel;
            $returnData = $objCityModel->saveData($data);
            return json_encode($returnData);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    } 
    function dataDownload(Request $request) {}
}