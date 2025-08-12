<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class BlankController extends Controller {

    function index() {
        $data['title'] = "Blank || HAQHAI";
        return view('blank.index',$data);
    }

}