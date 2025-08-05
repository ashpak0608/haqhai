<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccessPermissionsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $routeName = $request->route()->getName(); 
        $controllerName = explode('.', $routeName)[0];
        $objUser = new User();
        $user_id = Session::get('id');
        $getSingleData = $objUser->getSingleData($user_id);
        // Check if 'controller_name' in session matches the route prefix
        $accessPermissions = collect(Session::get('access_permissions'))->firstWhere('controller_name', $controllerName);
        $is_insert = $accessPermissions->is_insert ?? '';
        $is_update = $accessPermissions->is_update ?? '';
        $is_view = $accessPermissions->is_view ?? '';
        $is_delete = $accessPermissions->is_delete ?? '';

        $permissions = [
            'canAdd' => $is_insert,
            'canEdit' => $is_update,
            'canView' => $is_view,
            'canDelete' => $is_delete,
            'sub_module_name' => $accessPermissions->sub_module_name ?? '',
        ];
       
        $request->attributes->add(['permissions' => $permissions]);
        view()->share('permissions', $permissions);

        return $next($request);
    }
}
