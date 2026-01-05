<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AccessPermissionsMiddleware;

// =============== Auth => START ===============
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\VerifyOtpController;
use App\Http\Controllers\ResetPasswordController;
// =============== Auth => END ===============

// =============== Error => START ===============
use App\Http\Controllers\ErrorController;
// =============== Error => END ===============

// =============== Dashboard => START ===============
use App\Http\Controllers\DashboardController;
// =============== Dashboard => END ===============

// =============== Other Pages => START ===============
use App\Http\Controllers\BlankController;
// =============== Other Pages => END ===============

// =============== User Section => START ===============
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChangePasswordController;
// =============== User Section => END ===============

// =============== Accessibility => START ===============
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SubMenuController;
// =============== Accessibility => END ===============

// =============== Master => START ===============
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\RoadController;
use App\Http\Controllers\WardController;
use App\Http\Controllers\GoogleMapController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LandmarkController;
use App\Http\Controllers\UserLevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPageAccessController;
use App\Http\Controllers\LoginHistoryController;
use App\Http\Controllers\AreaController;
// =============== Master => END ===============

// =============== SYSTEM UTILITY ROUTES ===============
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return 'View cache cleared';
});

Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return 'Routes cache cleared';
});

Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return 'Config cache cleared';
}); 

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return 'Application cache cleared';
});

// Image display route
Route::get('/map-image/{filename}', function ($filename) {
    $path = storage_path('app/public/map-images/' . $filename);
    
    if (!file_exists($path)) {
        abort(404, "Map image not found: " . $filename);
    }
    
    return response()->file($path);
})->name('map.image.display');

// =============== AUTHENTICATION ROUTES ===============
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/', [LoginController::class, 'index']);
Route::post('login-check', [LoginController::class, 'loginCheck']);

Route::get('/forgot-password', [ForgotPasswordController::class, 'index']);
Route::get('/verify-otp', [VerifyOtpController::class, 'index']);
Route::get('/reset-password', [ResetPasswordController::class, 'index']);

// =============== ERROR ROUTES ===============
Route::get('/error-bad-request', [ErrorController::class, 'errorBadRequest']);
Route::get('/error-unauthorized', [ErrorController::class, 'errorUnauthorized']);
Route::get('/error-forbidden', [ErrorController::class, 'errorForbidden']);
Route::get('/error-not-found', [ErrorController::class, 'errorNotFound']);
Route::get('/error-internal-server', [ErrorController::class, 'errorInternalServer']);
Route::get('/error-bad-gateway', [ErrorController::class, 'errorBadGateway']);
Route::get('/error-service-unavailable', [ErrorController::class, 'errorServiceUnavailable']);
Route::get('/error-gateway-timeout', [ErrorController::class, 'errorGatewayTimeout']);

Route::get('/error-400', [ErrorController::class, 'error400']);
Route::get('/error-401', [ErrorController::class, 'error401']);
Route::get('/error-403', [ErrorController::class, 'error403']);
Route::get('/error-404', [ErrorController::class, 'error404']);
Route::get('/error-502', [ErrorController::class, 'error502']);
Route::get('/error-503', [ErrorController::class, 'error503']);
Route::get('/error-504', [ErrorController::class, 'error504']);
Route::get('/error-505', [ErrorController::class, 'error505']);

// =============== PROTECTED ROUTES (REQUIRE AUTH) ===============
Route::middleware(['auth'])->group(function () {   
    Route::middleware(['access.permissions'])->group(function () {
        
        Route::get('/logout', [LoginController::class, 'logout']);
        
        // =============== DASHBOARD ===============
        Route::get('dashboard', [DashboardController::class, 'index']);
        
        // =============== OTHER PAGES ===============
        Route::get('blank', [BlankController::class, 'index']);
        
        // =============== USER SECTION ===============
        Route::get('profile', [ProfileController::class, 'index']);
        Route::get('change-password', [ChangePasswordController::class, 'index']);
        Route::post('change-password/save', [ChangePasswordController::class, 'save']);
        
        // =============== ACCESSIBILITY ===============
        Route::prefix('menu')->name('menu.')->controller(MenuController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('filter', 'getFiltering')->name('filter');
            Route::get('add/{id?}', 'add')->name('add');
            Route::get('view/{id}', 'view')->name('view');
            Route::post('save', 'save')->name('save');
            Route::get('export', 'dataDownload')->name('data-download');
        });

        Route::prefix('sub-menu')->name('sub-menu.')->controller(SubMenuController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('filter', 'getFiltering')->name('filter');
            Route::get('add/{id?}', 'add')->name('add');
            Route::get('view/{id}', 'view')->name('view');
            Route::post('save', 'save')->name('save');
            Route::get('status/{status}/{id}', 'updateStatus')->name('status');
            Route::get('export', 'dataDownload')->name('data-download');
        });
        
        // =============== MASTER DATA ===============
        Route::prefix('country')->name('country.')->controller(CountryController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('filter', 'getFiltering')->name('filter');
            Route::get('add/{id?}', 'add')->name('add');
            Route::get('view/{id}', 'view')->name('view');
            Route::post('save', 'save')->name('save');
            Route::get('status/{status}/{id}', 'updateStatus')->name('status');
            Route::get('export', 'dataDownload')->name('data-download');
        });

        Route::prefix('state')->name('state.')->controller(StateController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('filter', 'getFiltering')->name('filter');
    Route::get('add/{id?}', 'add')->name('add');
    Route::get('view/{id}', 'view')->name('view');
    Route::post('save', 'save')->name('save');
    Route::get('status/{status}/{id}', 'updateStatus')->name('status');
    Route::get('export', 'dataDownload')->name('data-download');
    Route::delete('delete/{id}', 'destroy')->name('destroy'); // This should be inside the group
    Route::post('delete/{id}', 'destroy'); // For browsers that don't support DELETE
});

        
        Route::prefix('district')->name('district.')->controller(DistrictController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('filter', 'getFiltering')->name('filter');
    Route::get('add/{id?}', 'add')->name('add');
    Route::get('view/{id}', 'view')->name('view');
    Route::post('save', 'save')->name('save');
    Route::get('status/{status}/{id}', 'updateStatus')->name('status');
    Route::get('export', 'dataDownload')->name('data-download');
    Route::delete('delete/{id}', 'destroy')->name('destroy');
    Route::post('delete/{id}', 'destroy');
});
        
        Route::prefix('city')->name('city.')->controller(CityController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('filter', 'getFiltering')->name('filter');
    Route::get('add/{id?}', 'add')->name('add');
    Route::get('view/{id}', 'view')->name('view');
    Route::post('save', 'save')->name('save');
    Route::get('status/{status}/{id}', 'updateStatus')->name('status');
    Route::get('export', 'dataDownload')->name('data-download');
    Route::delete('delete/{id}', 'destroy')->name('destroy');
    Route::post('delete/{id}', 'destroy');
});

        Route::prefix('road')->name('road.')->controller(RoadController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('filter', 'getFiltering')->name('filter');
            Route::get('add/{id?}', 'add')->name('add');
            Route::get('view/{id}', 'view')->name('view');
            Route::post('save', 'save')->name('save');
            Route::get('status/{status}/{id}', 'updateStatus')->name('status');
            Route::get('export', 'dataDownload')->name('data-download');
        });

      // Ward Routes with Hierarchical Google Maps Integration
Route::prefix('ward')->name('ward.')->controller(WardController::class)->group(function () {
    // Basic CRUD routes
    Route::get('/', 'index')->name('index');
    Route::post('filter', 'getFiltering')->name('filter');
    Route::get('add/{id?}', 'add')->name('add');
    Route::get('view/{id}', 'view')->name('view');
    Route::post('save', 'save')->name('save');
    Route::get('status/{status}/{id}', 'updateStatus')->name('status');
    Route::get('export', 'dataDownload')->name('data-download');
    
    // Hierarchical Google Maps routes
    Route::get('map/{id?}', 'showMap')->name('map');
    Route::post('{id}/update-boundary', 'updateBoundary')->name('updateBoundary');
});

// Direct Google Map routes (if needed separately)
Route::get('/google-map/ward/{id}', [GoogleMapController::class, 'showMap'])->name('google-map.ward.show');

// =============== BUILDING ROUTES ===============
Route::prefix('building')->name('building.')->controller(BuildingController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('filter', 'getFiltering')->name('filter');
    Route::get('add/{id?}', 'add')->name('add');
    Route::get('view/{id}', 'view')->name('view');
    Route::post('save', 'save')->name('save');
    Route::get('status/{status}/{id}', 'updateStatus')->name('status');
    Route::get('export', 'dataDownload')->name('data-download');
    Route::get('map/{id?}', 'showMap')->name('map');
    Route::post('update-coordinates', 'updateCoordinates')->name('updateCoordinates');
    Route::post('get-cities-by-state', 'getCitiesByState')->name('getCitiesByState');
    Route::post('get-wards-by-city', 'getWardsByCity')->name('getWardsByCity');
    Route::post('get-landmarks-by-ward', 'getLandmarksByWard')->name('getLandmarksByWard');
    Route::delete('delete/{id}', 'destroy')->name('destroy');
    Route::post('delete/{id}', 'destroy');
    Route::post('get-filtering', 'getFiltering')->name('getFiltering');
});

Route::prefix('location')->name('location.')->controller(LocationController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('filter', 'getFiltering')->name('filter');
    Route::get('add/{id?}', 'add')->name('add');
    Route::get('view/{id}', 'view')->name('view');
    Route::post('save', 'save')->name('save');
    Route::get('status/{status}/{id}', 'updateStatus')->name('status');
    Route::get('export', 'dataDownload')->name('data-download');
    Route::delete('delete/{id}', 'destroy')->name('destroy');
    Route::post('delete/{id}', 'destroy');
});  
        
    Route::prefix('areas')->name('areas.')->controller(AreaController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('filter', 'getFiltering')->name('filter');
    Route::get('add/{id?}', 'add')->name('add');
    Route::get('view/{id}', 'view')->name('view');
    Route::post('save', 'save')->name('save');
    Route::get('status/{status}/{id}', 'updateStatus')->name('status');
    Route::get('export', 'dataDownload')->name('data-download');
    Route::delete('delete/{id}', 'destroy')->name('destroy');
    Route::post('delete/{id}', 'destroy');
});
            
        Route::prefix('landmark')->name('landmark.')->controller(LandmarkController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('filter', 'getFiltering')->name('filter');
        Route::get('add/{id?}', 'add')->name('add');
        Route::get('view/{id}', 'view')->name('view');
        Route::post('save', 'save')->name('save');
        Route::get('status/{status}/{id}', 'updateStatus')->name('status');
        Route::get('export', 'dataDownload')->name('data-download');
        Route::get('get-wards-by-city', 'getWardsByCity')->name('get-wards-by-city');
        Route::delete('delete/{id}', 'destroy')->name('destroy');
        Route::post('delete/{id}', 'destroy');
    });

        // =============== USER LEVEL ROUTES ===============
        // Comment out if UserLevelController doesn't exist
        // Route::prefix('user-level')->name('user-level.')->controller(UserLevelController::class)->group(function () {
        //     Route::get('/', 'index')->name('index');
        //     Route::post('filter', 'getFiltering')->name('filter');
        //     Route::get('add/{id?}', 'add')->name('add');
        //     Route::get('view/{id}', 'view')->name('view');
        //     Route::post('save', 'save')->name('save');
        //     Route::get('status/{status}/{id}', 'updateStatus')->name('status');
        //     Route::get('export', 'dataDownload')->name('data-download');
        // });

        Route::prefix('login-history')->name('login-history.')->controller(LoginHistoryController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('filter', 'getFiltering')->name('filter');
            Route::get('view/{id}', 'view')->name('view');
            Route::get('export', 'dataDownload')->name('data-download');
        });
        
        // =============== SETTINGS ===============
        Route::prefix('user')->name('user.')->controller(UserController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('filter', 'getFiltering')->name('filter');
            Route::get('add/{id?}', 'add')->name('add');
            Route::get('view/{id}', 'view')->name('view');
            Route::post('save', 'save')->name('save');
            Route::get('status/{status}/{id}', 'updateStatus')->name('status');
            Route::get('export', 'dataDownload')->name('data-download');
        });

        Route::prefix('user-page-access')->name('user-page-access.')->controller(UserPageAccessController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('filter', 'getFiltering')->name('filter');
            Route::get('add/{id?}', 'add')->name('add');
            Route::get('view/{id}', 'view')->name('view');
            Route::get('status/{status}/{id}', 'updateStatus')->name('status');
            Route::post('save', 'save')->name('save');
            Route::get('export', 'dataDownload')->name('data-download');
        });
        
        Route::prefix('role')->name('role.')->controller(RoleController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('filter', 'getFiltering')->name('filter');
            Route::get('add/{id?}', 'add')->name('add');
            Route::get('view/{id}', 'view')->name('view');
            Route::post('save', 'save')->name('save');
            Route::get('status/{status}/{id}', 'updateStatus')->name('status');
            Route::get('export', 'dataDownload')->name('data-download');
        });

        // =============== GOOGLE MAP ROUTES ===============
        Route::prefix('google-map')->name('google-map.')->controller(GoogleMapController::class)->group(function () {
            Route::get('/{moduleName}/{moduleId}', 'showMap')->name('show');
            Route::post('/save-drawing', 'saveDrawing')->name('saveDrawing');
            Route::get('/drawings/{moduleName}/{moduleId}', 'getDrawings')->name('getDrawings');
            Route::get('/drawing/{drawingId}', 'getDrawing')->name('getDrawing');
            Route::delete('/drawing/{drawingId}', 'deleteDrawing')->name('deleteDrawing');
            Route::post('/drawing/{drawingId}/set-default', 'setDefaultDrawing')->name('setDefault');
            Route::post('/drawing/{drawingId}/update-status', 'updateStatus')->name('updateStatus');
        });
        
    });
});