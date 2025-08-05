<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class MenuController extends Controller {

    function index() {
        $data['title'] = "Menu || Ajakin";
        return view('menu.index',$data);
    }

    // function getFiltering(Request $request) {}

    function add(Request $request , $id=null) { 
        $data['title'] = "Menu - Add || Ajakin";
        return view('menu.add',$data);
    }

    function view() {
        $data['title'] = "Menu - View || Ajakin";
        return view('menu.view',$data);
    }

    // function save(Request $request) {}

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
    } function dataDownload(Request $request) {}

}