<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\CityModel;
use App\Models\BuildingModel;
use App\Models\WardModel;
use App\Models\GoogleMap;
use App\validations\buildingMasterValidation;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\GoogleMapController;
use Illuminate\Support\Facades\Log;

class BuildingController extends Controller
{
    protected $table = 'buildings';

    public function index()
    {
        try {
            $data['title'] = "Building || HAQHAI";

            // load states (explicit columns)
           $data['states'] = DB::table('states')
    ->select('id', 'state_name', 'status')
    ->where('status', 0)
    ->whereNull('deleted_at') // This hides soft-deleted records
    ->orderBy('state_name', 'asc')
    ->get();

            // load cities (all cities; they now include state_id)
            $data['cities'] = DB::table('cities')
                ->select('id', 'city_name', 'state_id', 'district_id', 'status')
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->get();

            // wards
            $data['wards'] = CommonModel::getSingle('wards', ['status' => 0], ['id', 'ward_name', 'city_id', 'status']);
            
            // load active landmarks for initial filter list
            $data['landmarks'] = DB::table('landmarks')
                ->select('id', 'landmark_name', 'ward_id', 'city_id', 'area_id', 'status')
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->orderBy('landmark_name', 'asc')
                ->get();

            // Get pagination parameters
            $limit = request('limit', 10);
            $start = request('start', 0);
            $page = request('page', 1);
            
            // Calculate start based on page number
            if ($page > 1) {
                $start = ($page - 1) * $limit;
            }

            // default list params
            $param = [
                'start' => $start,
                'limit' => $limit,
                'state_id' => request('state_id'),
                'city_id' => request('city_id'),
                'ward_id' => request('ward_id'),
                'landmark_id' => request('landmark_id'),
                'building_name' => request('building_name'),
                'status' => request('status'),
            ];
            
            $lists = BuildingModel::getAllbuildingDetails($param);
            $data['lists'] = [];
            $data['total_count'] = 0;
            
            if ($lists['total_count'] > 0) {
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }

            // Pagination data
            $data['current_page'] = $page;
            $data['per_page'] = $limit;
            $data['last_page'] = ceil($data['total_count'] / $limit);
            $data['from'] = $start + 1;
            $data['to'] = min($start + $limit, $data['total_count']);

            return view('building.index', $data);
        } catch (\Throwable $e) {
            \Log::error('Index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Show Google Map for Building with Ward Boundary Context
     */
    public function showMap($id = null)
    {
        try {
            \Log::info("Building Google Map accessed for ID: " . $id);

            $building = null;
            $buildingName = 'New Building';
            $buildingIdentifier = 'New Building';
            $wardName = 'N/A';
            $cityName = 'N/A';
            $wardNumber = 'N/A';
            $searchQuery = '';
            $wardDefaultDrawing = null;
            $wardData = null;
            $existingBuildings = [];
            $defaultLat = '19.0760';
            $defaultLng = '72.8777';
            $zoomLevel = 12;

            if ($id) {
                $param = array('id' => $id);
                $buildingData = BuildingModel::getAllbuildingDetails($param);

                if ($buildingData['total_count'] > 0) {
                    $building = $buildingData['data'][0];
                    $buildingName = $building->building_name ?? 'Unnamed Building';
                    $buildingIdentifier = $building->building_name ?? 'Building #' . ($building->id ?? 'New');
                    $wardName = $building->ward_name ?? 'N/A';
                    $cityName = $building->city_name ?? 'N/A';
                    
                    // Get ward number if available
                    if (!empty($building->ward_id)) {
                        $ward = DB::table('wards')->where('id', $building->ward_id)->whereNull('deleted_at')->first();
                        if ($ward) {
                            $wardName = $ward->ward_name ?? $wardName;
                            $wardNumber = $ward->ward_number ?? 'N/A';
                        }
                    }

                    $searchQuery = $building->building_name . ', ' . $wardName . ', ' . $cityName;

                    // Get ward data for hierarchical context
                    if (!empty($building->ward_id)) {
                        $wardData = \App\Models\WardModel::find($building->ward_id);
                        $wardDefaultDrawing = \App\Models\GoogleMap::getDefaultDrawing('ward', $building->ward_id);
                        
                        // Get existing buildings in this ward (EXCLUDE CURRENT BUILDING)
                        $existingBuildings = DB::table('buildings')
                            ->select('id', 'building_name', 'latitude', 'longitude')
                            ->where('ward_id', $building->ward_id)
                            ->where('status', 0)
                            ->whereNull('deleted_at')
                            ->whereNotNull('latitude')
                            ->whereNotNull('longitude')
                            ->where('id', '!=', $building->id) // EXCLUDE CURRENT BUILDING
                            ->get()
                            ->toArray();
                    }

                    if (!empty($building->latitude) && !empty($building->longitude)) {
                        $defaultLat = $building->latitude;
                        $defaultLng = $building->longitude;
                        $zoomLevel = 18;
                    } else {
                        if ($wardDefaultDrawing && !empty($wardDefaultDrawing->center_lat) && !empty($wardDefaultDrawing->center_lng)) {
                            $defaultLat = $wardDefaultDrawing->center_lat;
                            $defaultLng = $wardDefaultDrawing->center_lng;
                            $zoomLevel = 15;
                        } else {
                            $cityCoords = $this->getCityCoordinates($building->city_id);
                            if ($cityCoords) {
                                $defaultLat = $cityCoords['latitude'];
                                $defaultLng = $cityCoords['longitude'];
                                $zoomLevel = 14;
                            }
                        }
                    }
                }
            } else {
                // For NEW building creation
                // If we have ward_id from request, load existing buildings for that ward
                if (request()->has('ward_id')) {
                    $wardId = request()->ward_id;
                    $wardData = \App\Models\WardModel::find($wardId);
                    if ($wardData) {
                        $wardName = $wardData->ward_name ?? 'N/A';
                        $wardNumber = $wardData->ward_number ?? 'N/A';
                        
                        // Get city name
                        $city = DB::table('cities')->where('id', $wardData->city_id)->whereNull('deleted_at')->first();
                        if ($city) {
                            $cityName = $city->city_name ?? 'N/A';
                        }
                        
                        $wardDefaultDrawing = \App\Models\GoogleMap::getDefaultDrawing('ward', $wardId);
                        $existingBuildings = DB::table('buildings')
                            ->select('id', 'building_name', 'latitude', 'longitude')
                            ->where('ward_id', $wardId)
                            ->where('status', 0)
                            ->whereNull('deleted_at')
                            ->whereNotNull('latitude')
                            ->whereNotNull('longitude')
                            ->get()
                            ->toArray();
                    }
                }
            }

            // Prepare enhanced module data
            $moduleData = [
                'title' => $buildingName,
                'identifier' => $buildingIdentifier,
                'latitude' => $defaultLat,
                'longitude' => $defaultLng,
                'zoom_level' => $zoomLevel,
                'search_query' => $searchQuery,
                'building_name' => $buildingName,
                'ward_name' => $wardName,
                'ward_number' => $wardNumber,
                'city_name' => $cityName,
                'auto_search' => true,
                'building' => $building ? (array)$building : null,
                'ward' => $wardData ? $wardData->toArray() : null,
                'city' => $cityName,
                'hasWardBoundary' => !empty($wardDefaultDrawing),
                'existingBuildings' => $existingBuildings
            ];

            if ($wardDefaultDrawing) {
                $moduleData['ward_default_drawing'] = $wardDefaultDrawing->toArray();
            }

            $googleMapController = new GoogleMapController();
            return $googleMapController->showMap('building', $id, $moduleData);

        } catch (\Exception $e) {
            \Log::error('Error in building Google Map: ' . $e->getMessage());
            return redirect()->route('building.index')->with('error', 'Error loading Google Map.');
        }
    }

    /**
     * Get city coordinates for fallback location
     */
    private function getCityCoordinates($cityId)
    {
        try {
            if (empty($cityId)) {
                return null;
            }
            $city = DB::table('cities')
                ->select('latitude', 'longitude')
                ->where('id', $cityId)
                ->whereNull('deleted_at')
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

    /**
     * Update building coordinates from map
     */
    public function updateCoordinates(Request $request)
    {
        try {
            $request->validate([
                'building_id' => 'required|exists:buildings,id',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric'
            ]);

            $buildingId = $request->building_id;
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            \Log::info("Updating coordinates for building {$buildingId}: {$latitude}, {$longitude}");

            $updated = DB::table('buildings')
                ->where('id', $buildingId)
                ->whereNull('deleted_at')
                ->update([
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'updated_by' => auth()->id(),
                    'updated_at' => now()
                ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Coordinates updated successfully!',
                    'building' => [
                        'id' => $buildingId,
                        'latitude' => $latitude,
                        'longitude' => $longitude
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Building not found!'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error updating coordinates: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating coordinates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show add/edit form
     */
    public function add(Request $request, $id = null)
    {
        try {
            $data['title'] = "Building - Add || HAQHAI";
            $data['singleData'] = [];

            if ($id != null) {
                $objBuildingModel = new BuildingModel();
                $singleDataResult = $objBuildingModel->getSingleData($id);

                if ($singleDataResult) {
                    $data['singleData'] = is_object($singleDataResult) ? json_decode(json_encode($singleDataResult), true) : $singleDataResult;

                    // Load only the cities for the selected state
                    $data['cities'] = DB::table('cities')
                        ->select('id', 'city_name', 'state_id', 'district_id', 'status')
                        ->where('status', 0)
                        ->whereNull('deleted_at')
                        ->where('state_id', $data['singleData']['state_id'])
                        ->get();

                    // Load wards for the selected city
                    $data['wards'] = DB::table('wards')
                        ->select('id', 'ward_name', 'city_id', 'status')
                        ->where('status', 0)
                        ->whereNull('deleted_at')
                        ->where('city_id', $data['singleData']['city_id'])
                        ->get();

                    // Load landmarks for the selected ward (prefer ward), fallback to city
                    $landmarkQuery = DB::table('landmarks')
                        ->select('id', 'landmark_name', 'ward_id', 'city_id', 'area_id', 'status')
                        ->where('status', 0)
                        ->whereNull('deleted_at');

                    if (!empty($data['singleData']['ward_id'])) {
                        $landmarkQuery->where('ward_id', $data['singleData']['ward_id']);
                    } elseif (!empty($data['singleData']['city_id'])) {
                        $landmarkQuery->where('city_id', $data['singleData']['city_id']);
                    }

                    $data['landmarks'] = $landmarkQuery->orderBy('landmark_name', 'asc')->get();
                } else {
                    return redirect()->route('building.index')->with('error', 'Building not found.');
                }
            } else {
                // For create mode, load all active cities/wards
                $data['cities'] = DB::table('cities')
                    ->select('id', 'city_name', 'state_id', 'district_id', 'status')
                    ->where('status', 0)
                    ->whereNull('deleted_at')
                    ->get();

                $data['wards'] = DB::table('wards')
                    ->select('id', 'ward_name', 'city_id', 'status')
                    ->where('status', 0)
                    ->whereNull('deleted_at')
                    ->get();

                // For create, load all active landmarks (or you may choose to leave blank)
                $data['landmarks'] = DB::table('landmarks')
                    ->select('id', 'landmark_name', 'ward_id', 'city_id', 'area_id', 'status')
                    ->where('status', 0)
                    ->whereNull('deleted_at')
                    ->orderBy('landmark_name', 'asc')
                    ->get();
            }

            // States are always loaded completely
            $data['states'] = DB::table('states')
    ->select('id', 'state_name', 'status')
    ->where('status', 0)
    ->whereNull('deleted_at') // This hides soft-deleted records
    ->orderBy('state_name', 'asc')
    ->get();

            return view('building.add', $data);
        } catch (\Throwable $e) {
            \Log::error('Add error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * View building details with hierarchical map data
     */
    public function view($id)
    {
        try {
            $data['title'] = "Building - View || HAQHAI";
            $param = ['id' => $id];
            $objBuildingModel = new BuildingModel();
            $viewLists = $objBuildingModel::getAllbuildingDetails($param);

            if ($viewLists['total_count'] > 0) {
                $data['views'] = $viewLists['data'][0];

                // Get Google Map drawings for this building
                $data['drawings'] = $this->getBuildingDrawings($id);
                $data['defaultDrawing'] = $this->getDefaultBuildingDrawing($id);

                // Get ward boundary data for context
                if (!empty($data['views']->ward_id)) {
                    $data['wardDefaultDrawing'] = GoogleMap::getDefaultDrawing('ward', $data['views']->ward_id);
                    $data['wardBoundary'] = $data['wardDefaultDrawing'] ? $data['wardDefaultDrawing']->drawing_data : null;
                }

                \Log::info("Found " . count($data['drawings']) . " Google Map drawings for building " . $id);
            } else {
                return redirect()->route('building.index')->with('error', 'Building not found.');
            }

            return view('building.view', $data);
        } catch (\Throwable $e) {
            \Log::error("View error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading view.');
        }
    }

    /**
     * Save building (create/update)
     */
    public function save(Request $request)
    {
        try {
            \Log::info('=== BUILDING SAVE METHOD CALLED ===');
            \Log::info('Request Data: ', $request->all());

            // Basic validation
            if (!$request->state_id || !$request->city_id || !$request->ward_id || !$request->building_name) {
                return response()->json(["status" => "warning", "message" => "State, City, Ward and Building Name are required"]);
            }

            // Validate lat/lon numeric if provided
            if ($request->latitude && !is_numeric($request->latitude)) {
                return response()->json(["status" => "warning", "message" => "Latitude must be a valid number"]);
            }
            if ($request->longitude && !is_numeric($request->longitude)) {
                return response()->json(["status" => "warning", "message" => "Longitude must be a valid number"]);
            }

            // Validate landmark if provided
            if ($request->filled('landmark_id')) {
                $landmark = DB::table('landmarks')
                    ->where('id', $request->landmark_id)
                    ->where('status', 0)
                    ->whereNull('deleted_at')
                    ->first();
                if (!$landmark) {
                    return response()->json(["status" => "warning", "message" => "Selected landmark is invalid or inactive."]);
                }
                // Ensure landmark belongs to selected ward (optional but recommended)
                if (!empty($request->ward_id) && isset($landmark->ward_id) && $landmark->ward_id != $request->ward_id) {
                    return response()->json(["status" => "warning", "message" => "Selected landmark doesn't belong to the selected ward."]);
                }
            }

            $objCommon = new CommonModel();
            // Include landmark_id in uniqueness check to avoid duplicates for same context (if null will be ignored by checkMultiUnique if it handles nulls)
            $uniqueFieldValue = [
                "state_id" => $request->state_id,
                "city_id" => $request->city_id,
                "ward_id" => $request->ward_id,
                "landmark_id" => $request->landmark_id ?? null,
                "building_name" => $request->building_name
            ];

            // Modify checkMultiUnique to exclude soft deleted records
            $uniqueCount = DB::table($this->table)
                ->where('state_id', $request->state_id)
                ->where('city_id', $request->city_id)
                ->where('ward_id', $request->ward_id)
                ->where('building_name', $request->building_name)
                ->whereNull('deleted_at')
                ->when($request->landmark_id, function($query) use ($request) {
                    return $query->where('landmark_id', $request->landmark_id);
                }, function($query) {
                    return $query->whereNull('landmark_id');
                })
                ->when($request->id, function($query, $id) {
                    return $query->where('id', '!=', $id);
                })
                ->count();

            \Log::info('Unique check count: ' . $uniqueCount);

            if ($uniqueCount > 0) {
                return response()->json(["status" => "exist", "message" => "Building with this name already exists in the selected State, City, Ward and Landmark combination!"]);
            }

            $objBuildingModel = new BuildingModel();
            $post = $request->all();

            // Ensure status default
            if (!isset($post['status'])) {
                $post['status'] = '0';
            }

            $returnData = $objBuildingModel->saveData($post);
            \Log::info('Save result: ', $returnData);

            return response()->json($returnData);
        } catch (\Throwable $e) {
            \Log::error('Error in save: ' . $e->getMessage());
            return response()->json(['status' => 'warning', 'message' => $e->getMessage()]);
        }
    }

    // AJAX: return cities filtered by state
    public function getCitiesByState(Request $request)
    {
        try {
            $state_id = $request->state_id;
            $cities = DB::table('cities')
                ->select('id', 'city_name', 'state_id', 'district_id')
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->when($state_id, function ($q) use ($state_id) {
                    return $q->where('state_id', $state_id);
                })
                ->orderBy('city_name', 'asc')
                ->get();

            return response()->json($cities);
        } catch (\Throwable $e) {
            \Log::error('getCitiesByState error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // AJAX: return wards filtered by city
    public function getWardsByCity(Request $request)
    {
        try {
            $city_id = $request->city_id;
            $wards = DB::table('wards')
                ->select('id', 'ward_name', 'city_id')
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->when($city_id, function ($q) use ($city_id) {
                    return $q->where('city_id', $city_id);
                })
                ->orderBy('ward_name', 'asc')
                ->get();

            return response()->json($wards);
        } catch (\Throwable $e) {
            \Log::error('getWardsByCity error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // AJAX: return landmarks filtered by ward (or by city if ward not provided)
    public function getLandmarksByWard(Request $request)
    {
        try {
            $ward_id = $request->ward_id;
            $city_id = $request->city_id;

            $query = DB::table('landmarks')
                ->select('id', 'landmark_name', 'ward_id', 'city_id', 'area_id')
                ->where('status', 0)
                ->whereNull('deleted_at');

            if (!empty($ward_id)) {
                $query->where('ward_id', $ward_id);
            } elseif (!empty($city_id)) {
                $query->where('city_id', $city_id);
            }

            $landmarks = $query->orderBy('landmark_name', 'asc')->get();

            return response()->json($landmarks);
        } catch (\Throwable $e) {
            \Log::error('getLandmarksByWard error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStatus($status, $id)
    {
        try {
            $id = (int) $id;
            $status = (int)$status === 0 ? 1 : 0;
            $data = ['status' => $status, 'id' => $id];
            $objBuildingModel = new BuildingModel;
            $returnData = $objBuildingModel->saveData($data);
            return json_encode($returnData);
        } catch (\Throwable $e) {
            $returnData = ['status' => 'warning', 'message' => $e->getMessage()];
            return json_encode($returnData);
        }
    }

    public function dataDownload(Request $request)
    {
        // Placeholder: implement export logic if necessary
        return response()->json(['status' => 'warning', 'message' => 'Export not implemented.']);
    }

    public function getFiltering(Request $request)
    {
        try {
            $data = [];
            $param = [];
            $param['start'] = $request->start ?? 0;
            $param['limit'] = $request->limit ?? 10;
            $param['state_id'] = $request->state_id;
            $param['city_id'] = $request->city_id;
            $param['ward_id'] = $request->ward_id;
            $param['landmark_id'] = $request->landmark_id;
            $param['building_name'] = $request->building_name;
            $param['status'] = $request->status;

            $lists = BuildingModel::getAllbuildingDetails($param);
            $data['total_count'] = $lists['total_count'];
            $data['lists'] = [];
            $data['message'] = "No record found!";

            if ($lists['total_count'] > 0) {
                $start = $request->start ?? 0;
                $data['lists'] = $lists['data'];
                $data['status'] = 'success';
                $data['message'] = "Showing " . ($start + 1) . " to " . ($start + count($lists['data'])) . " of " . $lists['total_count'] . " records.";
            }

            return response()->json($data);
        } catch (\Throwable $e) {
            $returnData = ['status' => 'warning', 'message' => $e->getMessage()];
            return response()->json($returnData);
        }
    }

    /**
     * Get drawings for building using direct DB query (to avoid model conflict)
     */
    private function getBuildingDrawings($buildingId)
    {
        return DB::table('google_maps')
            ->where('module_name', 'building')
            ->where('module_id', $buildingId)
            ->where('status', 'active')
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get default drawing for building using direct DB query
     */
    private function getDefaultBuildingDrawing($buildingId)
    {
        return DB::table('google_maps')
            ->where('module_name', 'building')
            ->where('module_id', $buildingId)
            ->where('status', 'active')
            ->where('is_default', true)
            ->first();
    }
    
    /**
     * Get buildings in a specific ward for map display
     */
    public function getBuildingsInWard($wardId)
    {
        try {
            \Log::info("Fetching buildings for ward: " . $wardId);
            
            $buildings = DB::table('buildings')
                ->select('id', 'building_name', 'latitude', 'longitude')
                ->where('ward_id', $wardId)
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get();
                
            \Log::info("Found " . $buildings->count() . " buildings for ward " . $wardId);
                
            return response()->json([
                'success' => true,
                'buildings' => $buildings
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching buildings for ward ' . $wardId . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching buildings: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Soft delete building
     */
    public function destroy(Request $request, $id)
    {
        try {
            Log::info('Building delete request received:', [
                'id' => $id,
                'method' => $request->method(),
                'ajax' => $request->ajax()
            ]);

            $id = (int) $id;
            if ($id <= 0) {
                Log::warning('Invalid ID for delete:', ['id' => $id]);
                $resp = ['status' => 'warning', 'message' => 'Invalid ID'];
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json($resp, 400);
                }
                return redirect()->back()->with('status', 'warning')->with('message', 'Invalid ID');
            }

            // Check if building exists and is not already deleted
            $objModel = new BuildingModel();
            $row = $objModel->getSingleData($id);
            if (!$row) {
                Log::warning('Building not found for delete:', ['id' => $id]);
                $resp = ['status' => 'warning', 'message' => 'Building not found or already deleted'];
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json($resp, 404);
                }
                return redirect()->back()->with('status', 'warning')->with('message', $resp['message']);
            }

            Log::info('Deleting building:', ['id' => $id, 'building_name' => $row['building_name']]);

            // Perform soft delete: set deleted_at and deleted_by
            $deletedBy = Session::get('id') ?? null;
            $result = DB::table('buildings')->where('id', $id)->update([
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => $deletedBy
            ]);

            Log::info('Delete result:', ['affected_rows' => $result, 'id' => $id]);

            $resp = ['status' => 'success', 'message' => 'Building deleted successfully', 'id' => $id];

            // Always return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($resp);
            }

            // Only redirect for non-AJAX requests
            return redirect()->route('building.index')->with('status', 'success')->with('message', $resp['message']);
        } catch (\Throwable $e) {
            Log::error('Building delete error:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $resp = ['status' => 'warning', 'message' => $e->getMessage()];
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($resp, 500);
            }
            return redirect()->back()->with('status', 'warning')->with('message', $e->getMessage());
        }
    }
    
    /**
     * Get city name by ID
     */
    private function getCityName($cityId)
    {
        try {
            if (empty($cityId)) {
                return 'N/A';
            }
            $city = DB::table('cities')
                ->select('city_name')
                ->where('id', $cityId)
                ->whereNull('deleted_at')
                ->first();

            return $city ? $city->city_name : 'N/A';
        } catch (\Exception $e) {
            \Log::error('Error getting city name: ' . $e->getMessage());
            return 'N/A';
        }
    }
}