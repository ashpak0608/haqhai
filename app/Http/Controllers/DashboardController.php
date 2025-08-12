<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class DashboardController extends Controller {

    function index() {
        $data['title'] = "Dashboard || HAQHAI";
        return view('dashboard.index',$data);
    }

}