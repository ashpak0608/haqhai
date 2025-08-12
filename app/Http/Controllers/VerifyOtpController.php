<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class VerifyOtpController extends Controller {

    function index() {
        $data['title'] = "Verify OTP || HAQHAI";
        return view('auth.verify_otp',$data);
    }

}