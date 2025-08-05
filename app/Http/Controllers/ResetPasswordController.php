<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ResetPasswordController extends Controller {

    function index() {
        $data['title'] = "Reset Password || Ajakin";
        return view('auth.reset_password',$data);
    }

}