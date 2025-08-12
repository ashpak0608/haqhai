<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use PDF;
use File;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\CountryMasterModel;
use App\validations\CountryMasterValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CountryController extends Controller {
    
    protected $table = 'country_master';

    function index() {
        try{
            $data['title'] = "Country || HAQHAI";
            $param=array(
                    'start' => 0,
                    'limit' => 10,
                );
            $lists = CountryMasterModel::getAllCountryDetails($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('country.index',$data);
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
            $param['country_name'] = $request->country_name;
            $param['status'] = $request->status;
            $objCountryMasterModel = new CountryMasterModel;
            $lists = $objCountryMasterModel->getAllCountryDetails($param);
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
            $data['title'] = "Country - Add || HAQHAI";
            if($id != null) {
                $data['id'] = $id;
                $objCountryMasterModel = new CountryMasterModel();
                $data['singleData'] = $objCountryMasterModel->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }
            return view('country.add',$data);
        }catch(\Throwable $e){
                $returnData = array('status' => 'warning', 'message' => $e->getMessage());
                return json_encode($returnData);
        }
    }

    function view($id) {
        try{
            $data['title'] = "Country - View || HAQHAI";
            $param = array('id' => $id);
            $viewLists = CountryMasterModel::getAllCountryDetails($param);
            $data['views'] = $viewLists['data'][0];
            return view('country.view',$data);
        }catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function save(Request $request) {
        try{
            $returnData = array();
            $CountryMasterValidation = new CountryMasterValidation();
            $validationResult = $CountryMasterValidation->validate($request->all());
            if ($validationResult !== null) {
                return json_encode($validationResult);
            }
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "country_name" => $request->country_name,
            ];
            $uniqueCount = $objCommon->checkMultiUnique($this->table,$uniqueFieldValue,$request["id"]);
            if ($uniqueCount > 0) {
                $returnData = ["status" => "exist","message" => "Country Name already exists!","unique_field" => $uniqueFieldValue];
                return json_encode($returnData);
            }
            $objCountryMasterModel = new CountryMasterModel();
            $post = $request->all();
            $returnData = $objCountryMasterModel->saveData($post);
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
            $objCountryMasterModel = new CountryMasterModel;
            $returnData = $objCountryMasterModel->saveData($data);
            return json_encode($returnData);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function dataDownload(Request $request) {
        try{
           $param = [];
             $param=array(
                    'start' => $request->query('start') ?? 0,
                    'limit' => $request->query('limit') ?? 10,
                    'country_name' => $request->query('country_name') ?? '',
                );
            $lists = CountryMasterModel::getAllCountryDetails($param);
            $countries = $lists['data'];

            $fileName = 'Country Details.csv';
            $columns = array(
                    'Sr.No',
                    'Country Name',
                    'Status'
                );        
                 
                 $headers = array(
                        "Content-type"        => "text/csv",
                        "Content-Disposition" => "attachment; filename=$fileName",
                        "Pragma"              => "no-cache",
                        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                        "Expires"             => "0"
                    );

               $callback = function() use($countries, $columns) {
                        $file = fopen('php://output', 'w');
                         fputcsv($file, $columns);

                         foreach($countries as $key => $details){
                            $row['sr_no'] = $key+1;
                            $row['country_name'] = $details->country_name;
                            $row['status'] = $details->status == 1 ? 'Active' : 'Inactive';
                            fputcsv($file, array(
                                $row['sr_no'],
                                $row['country_name'],
                                $row['status']
                            ));
                    }
                   fclose($file);
                  };
             return response()->stream($callback, 200, $headers);
        }catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }
}