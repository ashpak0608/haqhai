<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ErrorController extends Controller {

    function errorBadRequest() {
        return view('auth.error_page.bad_request');
    }

    function errorUnauthorized() {
        return view('auth.error_page.unauthorized');
    }

    function errorForbidden() {
        return view('auth.error_page.forbidden');
    }

    function errorNotFound() {
        return view('auth.error_page.not_found');
    }

    function errorInternalServer() {
        return view('auth.error_page.internal_server');
    }

    function errorBadGateway() {
        return view('auth.error_page.bad_gateway');
    }

    function errorServiceUnavailable() {
        return view('auth.error_page.service_unavailable');
    }

    function errorGatewayTimeout() {
        return view('auth.error_page.gateway_timeout');
    }

    function error400() {
        return view('auth.error_page.error_400');
    }

    function error401() {
        return view('auth.error_page.error_401');
    }

    function error403() {
        return view('auth.error_page.error_403');
    }

    function error404() {
        return view('auth.error_page.error_404');
    }

    function error502() {
        return view('auth.error_page.error_502');
    }

    function error503() {
        return view('auth.error_page.error_503');
    }

    function error504() {
        return view('auth.error_page.error_504');
    }

    function error505() {
        return view('auth.error_page.error_505');
    }

}