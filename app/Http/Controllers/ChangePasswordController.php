<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller {

    protected $table = 'users';

    function index() {
        try{
            $data['title'] = "Change Password || Ajakin";
            $param=array( 'id' => Session::get('id'));
            $lists = User::details($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'][0];
                $data['total_count'] = $lists['total_count'];
            }
            return view('user_section.change_password',$data);
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
    }


    function passwordUpdate(Request $request){
        try{
            $validator = Validator::make($request->all(), ([
                'current_password' => ['required'],
                'new_password' => ['required'],
                'new_confirm_password' => ['same:new_password'],
            ]));

            if ($validator->fails()) {
                    $returnData = array('status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors());
                    return json_encode($returnData);
            }

            $id = Session::get('id');
            $objUser = new User;
            $singleData = $objUser->getSingleData($id);
            $password = $singleData['password'];
            if (Hash::check($request->current_password, $password)) {
                User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
                Session::flush();
                $returnData = array('status' => 'success', 'message' => 'Password change successfully!');
                return json_encode($returnData);
            }else{
                $returnData = array('status' => 'error', 'message' => 'Current password not match with old password!');
                return json_encode($returnData);
            }
    }
    catch(\Exception $e){
        $returnData = array('status' => 'error', 'message' => $e->getMessage());
        return json_encode($returnData);
        
    }
}

}