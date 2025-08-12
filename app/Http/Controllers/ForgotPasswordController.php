<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ForgotPasswordController extends Controller {

    function index() {
        $data['title'] = "Forgot Password || HAQHAI";
        return view('auth.forgot_password',$data);
    }

}