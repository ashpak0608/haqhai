<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\CityModel;
use App\Models\WardModel;
use App\Models\GoogleMap;
use App\validations\WardMasterValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\GoogleMapController;
use Illuminate\Support\Facades\Log;

class WardController extends Controller {
    
    protected $table = 'wards';

    public function index() {
       try{
            $data['title'] = "Ward || HAQHAI";
            
            // Updated to exclude soft-deleted cities
            $data['cities'] = DB::table('cities')
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->get();

            $param=array(
                'start' => 0,
                'limit' => 10,
            );
            $lists = WardModel::getAllWardDetails($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('ward.index',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    /**
     * Show Google Map for Ward with City Context
     */
    public function showMap($id = null)
    {
        try {
            \Log::info("Ward Google Map accessed for ID: " . $id);
            
            $ward = null;
            $wardName = 'New Ward';
            $wardIdentifier = 'New Ward';
            $wardNumber = 'N/A';
            $cityName = 'N/A';
            $cityData = null;
            $defaultLat = '19.0760';
            $defaultLng = '72.8777';
            $zoomLevel = 12;
            
            if ($id) {
                $param = array('id' => $id);
                $wardData = WardModel::getAllWardDetails($param);
                
                if ($wardData['total_count'] > 0) {
                    $ward = $wardData['data'][0];
                    $wardName = $ward->ward_name ?? 'Unnamed Ward';
                    $wardIdentifier = $ward->ward_name ?? 'Ward #' . ($ward->id ?? 'New');
                    $wardNumber = $ward->ward_number ?? 'N/A';
                    $cityName = $ward->city_name ?? 'N/A';
                    
                    if (!empty($ward->city_id)) {
                        $cityData = DB::table('cities')->where('id', $ward->city_id)->first();
                    }
                    
                    $cityCoords = $this->getCityCoordinates($ward->city_id);
                    if ($cityCoords) {
                        $defaultLat = $cityCoords['latitude'];
                        $defaultLng = $cityCoords['longitude'];
                        $zoomLevel = 13;
                        \Log::info("Using city coordinates for ward map: " . $defaultLat . ", " . $defaultLng);
                    }
                }
            }
            
            $moduleData = [
                'title' => $wardName,
                'identifier' => $wardIdentifier,
                'latitude' => $defaultLat,
                'longitude' => $defaultLng,
                'zoom_level' => $zoomLevel,
                'ward_name' => $wardName,
                'ward_number' => $wardNumber,
                'city_name' => $cityName,
                'ward' => $ward ? (array)$ward : null,
                'city' => $cityData ? (array)$cityData : null
            ];

            \Log::info("Ward map module data:", $moduleData);

            $googleMapController = new GoogleMapController();
            return $googleMapController->showMap('ward', $id, $moduleData);
            
        } catch (\Exception $e) {
            \Log::error('Error in ward Google Map: ' . $e->getMessage());
            return redirect()->route('ward.index')->with('error', 'Error loading Google Map.');
        }
    }

    private function getCityCoordinates($cityId)
    {
        try {
            $city = DB::table('cities')
                ->select('latitude', 'longitude')
                ->where('id', $cityId)
                ->first();
                
            if ($city && !empty($city->latitude) && !empty($city->longitude)) {
                return [
                    'latitude' => $city->latitude,
                    'longitude' => $city->longitude
                ];
            }
            
            return null;
        } catch (\Exception $e) {
            \Log::error('Error getting city coordinates: ' . $e->getMessage());
            return null;
        }
    }

    public function updateBoundary(Request $request, $id)
    {
        try {
            $request->validate([
                'boundary_data' => 'required|string',
                'center_lat' => 'required|numeric',
                'center_lng' => 'required|numeric'
            ]);

            $ward = WardModel::find($id);
            if (!$ward) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ward not found'
                ], 404);
            }

            $updated = DB::table('wards')
                ->where('id', $id)
                ->update([
                    'boundary_data' => $request->boundary_data,
                    'center_lat' => $request->center_lat,
                    'center_lng' => $request->center_lng,
                    'updated_by' => auth()->id(),
                    'updated_at' => now()
                ]);

            if ($updated) {
                \Log::info("Ward boundary updated", [
                    'ward_id' => $id,
                    'center_lat' => $request->center_lat,
                    'center_lng' => $request->center_lng
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Ward boundary updated successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to update ward boundary'
            ], 500);

        } catch (\Exception $e) {
            \Log::error('Error updating ward boundary:', [
                'ward_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating ward boundary: ' . $e->getMessage()
            ], 500);
        }
    }

    function getFiltering(Request $request) {
        try{
            $data = array();
            $param = array();
            $param['start'] = $request->start;
            $param['limit'] = $request->limit;
            $param['city_id'] = $request->city_id;
            $param['ward_name'] = $request->ward_name;
            $param['status'] = $request->status;
            $objWardModel = new WardModel;
            $lists = $objWardModel->getAllWardDetails($param);
            $data['total_count'] = $lists['total_count'];
            $data['lists'] = array();
            $data['message'] = "No record found!";
            if($lists['total_count'] > 0){
                $count = count($lists['data'])+ $request->start;
                $data['lists'] = $lists['data'];
                $data['status'] = 'success';
                $data['message'] = "Showing ".++$request->start." to ". $count ." of ".$lists['total_count']." records.";
            }
            return json_encode($data);
        }catch(\Throwable $e){         
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function add(Request $request , $id=null) { 
        try{
            $data['title'] = "Ward - Add || HAQHAI";
            if($id != null) {
                $data['id'] = $id;
                $objWardModel = new WardModel();
                $data['singleData'] = $objWardModel->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }

            // Updated to exclude soft-deleted cities
            $data['cities'] = DB::table('cities')
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->get();

            return view('ward.add',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function view($id) {
        try{
            $data['title'] = "Ward - View || HAQHAI";
            $param = array('id' => $id);
            $objWardModel = new WardModel();
            $viewLists = $objWardModel::getAllWardDetails($param);
            
            if ($viewLists['total_count'] > 0) {
                $data['views'] = $viewLists['data'][0];
                
                $data['drawings'] = GoogleMap::getModuleDrawings('ward', $id);
                $data['defaultDrawing'] = GoogleMap::getDefaultDrawing('ward', $id);
                
                $data['buildings'] = DB::table('buildings')
                    ->select('id', 'building_name', 'latitude', 'longitude')
                    ->where('ward_id', $id)
                    ->where('status', 0)
                    ->get();
                
                \Log::info("Found " . count($data['drawings']) . " Google Map drawings for ward " . $id);
                \Log::info("Found " . count($data['buildings']) . " buildings in ward " . $id);
            } else {
                return redirect()->route('ward.index')->with('error', 'Ward not found.');
            }
            
            return view('ward.view',$data);
        }
        catch(\Throwable $e){
            \Log::error("View error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading view.');
        }
    }

    function save(Request $request) {
        try{
            \Log::info('=== WARD SAVE METHOD CALLED ===');
            \Log::info('Request Data: ', $request->all());
            
            $returnData = array();
            
            if (!$request->city_id || !$request->ward_name || !$request->ward_number) {
                return json_encode(["status" => "warning", "message" => "All fields are required"]);
            }
            
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "city_id" => $request->city_id,
                "ward_name" => $request->ward_name,
                "ward_number" => $request->ward_number
            ];
            
            $uniqueCount = $objCommon->checkMultiUnique($this->table, $uniqueFieldValue, $request["id"]);
            \Log::info('Unique check count: ' . $uniqueCount);
            
            if ($uniqueCount > 0) {
                $returnData = ["status" => "exist", "message" => "City and Ward already exists!"];
                return json_encode($returnData);
            }
            
            $objWardModel = new WardModel();
            $post = $request->all();
            
            $returnData = $objWardModel->saveData($post);
            \Log::info('Save result: ', $returnData);
            
            return json_encode($returnData);
            
        } catch(\Throwable $e){
            \Log::error('Error in save method: ' . $e->getMessage());
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    }

    function updateStatus($status, $id){
        try{
            $id = (int) $id;
            $status = (int)$status === 0 ? 1 : 0;
            $data = array('status' => $status , 'id' => $id);
            $objWardModel = new WardModel;
            $returnData = $objWardModel->saveData($data);
            return json_encode($returnData);
        }catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return json_encode($returnData);
        }
    } 
    
    function dataDownload(Request $request) {
        return response()->json(['status' => 'warning', 'message' => 'Export not implemented.']);
    }
}