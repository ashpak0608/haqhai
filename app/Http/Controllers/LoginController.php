<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

class LoginController extends Controller {

    function index() {
        $data['title'] = "Login || HAQHAI";
        return view('auth.login',$data);
    }

    function loginCheck(Request $request) {
		try{
			$returnData = array();
			$validator = Validator::make($request->all(), ([
				'email_id' => 'required',
				'password' => 'required',
			]));

			if ($validator->fails()) {
				$returnData = array('status' => 'error', 'message' => $validator->errors());
				return json_encode($returnData);
			}
			$arry['email_id'] = $request['email_id'];
			$arry['password'] = $request['password'];
			$arry['status'] = 0;
			$objUser = New User();
			$result = $objUser->checkLoginDetails($arry);
			
			if($result['status'] == 'success') {
				$getData = $result['data'];
				$userDetails = $getData[0];
				$accessPermissions = $getData[1];
				if(Auth::attempt($arry)) {
					foreach ($userDetails as $key => $value) {
						Session::put($key, $value);
					}
					Session::put('access_permissions', $accessPermissions);
					$userId = $userDetails->id; 
					User::saveUserBrowserDetails($userId);
					$returnData = array('status' => 'success', 'message' => 'Login Successfully');
					return json_encode($returnData);
				}
			}
			else {
				$returnData = array('status' => 'warning', 'message' => 'Invalid Email/password entered.Please try again.');
				return json_encode($returnData);
			}
		}catch(\Throwable $e){
			$returnData = array('status' => 'warning', 'message' => $e->getMessage());
			return json_encode($returnData);
		}
    }

    function logout() {
    	Session::flush();
		return redirect('/');
    }

}