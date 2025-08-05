<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\CustomerCategoryModel;
use App\validations\CustomerCategoryValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class CustomerCategoryController extends Controller {

    protected $table = 'customer_category';

    function index() {
        try{
            $data['title'] = "Customer Category || Ajakin";
            $param=array(
                'start' => 0,
                'limit' => 10,
            );
            $lists = CustomerCategoryModel::getAllCustomerCategoryDetails($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('customer_category.index',$data);
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
            $param['category_name'] = $request->category_name;
            $param['status'] = $request->status;
            $objCustomerCategoryModel = new CustomerCategoryModel;
            $lists = $objCustomerCategoryModel->getAllCustomerCategoryDetails($param);
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
            $data['title'] = "Customer Category - Add || Ajakin";
            if($id != null) {
                $data['id'] = $id;
                $objCustomerCategoryModel = new CustomerCategoryModel();
                $data['singleData'] = $objCustomerCategoryModel->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }
            return view('customer_category.add',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function view($id) {
        try{
            $data['title'] = "Customer Category - View || Ajakin";
            $param = array('id' => $id);
            $viewLists = CustomerCategoryModel::getAllCustomerCategoryDetails($param);
            $data['views'] = $viewLists['data'][0];
            return view('customer_category.view',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function save(Request $request) {
        try{
            $returnData = array();
            $CustomerCategoryValidation = new CustomerCategoryValidation();
            $validationResult = $CustomerCategoryValidation->validate($request->all());
            if ($validationResult !== null) {
                return json_encode($validationResult);
            }
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "category_name" => $request->category_name,
            ];
            $uniqueCount = $objCommon->checkMultiUnique($this->table,$uniqueFieldValue,$request["id"]);
            if ($uniqueCount > 0) {
                $returnData = ["status" => "exist","message" => "Category Name already exists!","unique_field" => $uniqueFieldValue];
                return json_encode($returnData);
            }
            $objCustomerCategoryModel = new CustomerCategoryModel();
            $post = $request->all();
            $returnData = $objCustomerCategoryModel->saveData($post);
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
            $objCustomerCategoryModel = new CustomerCategoryModel;
            $returnData = $objCustomerCategoryModel->saveData($data);
            return json_encode($returnData);
        }catch(Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    } function dataDownload(Request $request) {}

}