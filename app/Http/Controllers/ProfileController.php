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

class ProfileController extends Controller {

    function index() {
        try{
            $data['title'] = "Profile || Ajakin";
            $param=array( 'id' => Session::get('id'));
            $lists = User::details($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'][0];
                $data['total_count'] = $lists['total_count'];
            }
            return view('user_section.profile',$data);
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

}