<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use Illuminate\Http\Request;
use App\Models\CommonModel;
use App\Models\LandmarkMasterModel;
use App\validations\LandmarkMasterValidation;
use Illuminate\Routing\Controller as BaseController;
use Log;

class LandmarkController extends Controller {

    protected $table = 'landmarks';
    
    function index() {
        try{
            $data['title'] = "Landmark || HAQHAI";
            
            // Get only non-deleted records
            $data['areas'] = DB::table('areas')
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->get();
                
            $data['cities'] = DB::table('cities')
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->get();
                
            $param=array(
                'start' => 0,
                'limit' => 10,
            );
            $lists = LandmarkMasterModel::details($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('landmark.index',$data);
        }
        catch(\Throwable $e){
            Log::error('Landmark index error: ' . $e->getMessage());
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return response()->json($returnData, 500);
        }
    }

    function getFiltering(Request $request) {
        try{
            $data = array();
            $param = array();
            $param['start'] = (int) ($request->start ?? 0);
            $param['limit'] = (int) ($request->limit ?? 10);
            $param['landmark_name'] = $request->landmark_name ?? null;
            $param['area_id'] = $request->area_id ?? null;
            $param['city_id'] = $request->city_id ?? null;
            $param['ward_id'] = $request->ward_id ?? null;
            $param['status'] = $request->status ?? null;
            
            $lists = LandmarkMasterModel::details($param);
            $data['total_count'] = $lists['total_count'];
            $data['lists'] = array();
            $data['message'] = "No record found!";
            $data['status'] = 'empty';
            
            if($lists['total_count'] > 0){
                $count = count($lists['data']) + $param['start'];
                $data['lists'] = $lists['data'];
                $data['status'] = 'success';
                $data['message'] = "Showing " . ($param['start'] + 1) . " to " . $count . " of " . $lists['total_count'] . " records.";
                
                // Add serial numbers for each row
                foreach ($data['lists'] as $index => $list) {
                    $list->serial_no = $param['start'] + $index + 1;
                }
            }
            return response()->json($data);
        }catch(\Throwable $e){         
            Log::error('Landmark filter error: ' . $e->getMessage());
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return response()->json($returnData, 500);
        }
    }

  function add(Request $request, $id=null) { 
    try{
        $data['title'] = ($id ? "Edit" : "Add") . " Landmark || HAQHAI";
        
        if($id != null) {
            $data['id'] = $id;
            $objLandmarkModel = new LandmarkModel();
            $data['singleData'] = $objLandmarkModel->getSingleData($id);
            
            // Get wards for the selected city in edit mode
            if(isset($data['singleData']['city_id']) && $data['singleData']['city_id']) {
                $data['wards'] = DB::table('wards')
                    ->where('status', 0)
                    ->where('city_id', $data['singleData']['city_id'])
                    ->whereNull('deleted_at')
                    ->get();
            } else {
                $data['wards'] = collect([]);
            }
        } else {
            $data['singleData'] = [
                'id' => null,
                'area_id' => null,
                'city_id' => null,
                'ward_id' => null,
                'landmark_name' => null
            ];
            $data['wards'] = collect([]);
        }
        
        // Get non-deleted records
        $data['areas'] = DB::table('areas')
            ->where('status', 0)
            ->whereNull('deleted_at')
            ->get();
            
        $data['cities'] = DB::table('cities')
            ->where('status', 0)
            ->whereNull('deleted_at')
            ->get();
            
        return view('landmark.add',$data);
    }
    catch(\Throwable $e){
        Log::error('Landmark add error: ' . $e->getMessage());
        $returnData = array('status' => 'warning', 'message' => $e->getMessage());
        return response()->json($returnData, 500);
    }
}

    function view($id) {
    try{
        $data['title'] = "View Landmark || HAQHAI";
        $param = array('id' => $id);
        $viewLists = LandmarkMasterModel::details($param);
        
        if($viewLists['total_count'] > 0){
            $data['view'] = $viewLists['data'][0];
            
            // Debug: Log the data to see what's being fetched
            Log::info('Landmark view data:', (array)$data['view']);
            
            // If ward_name is empty but ward_id exists, fetch ward separately
            if(empty($data['view']->ward_name) && !empty($data['view']->ward_id)) {
                $ward = DB::table('wards')
                    ->where('id', $data['view']->ward_id)
                    ->whereNull('deleted_at')
                    ->first();
                
                if($ward) {
                    $data['view']->ward_name = $ward->ward_name;
                }
            }
        } else {
            $data['view'] = null;
        }
        
        return view('landmark.view',$data);
    }
    catch(\Throwable $e){
        Log::error('Landmark view error: ' . $e->getMessage());
        $returnData = array('status' => 'warning', 'message' => $e->getMessage());
        return response()->json($returnData, 500);
    }
}

    function save(Request $request) {
        try{
            Log::info('Landmark Save Request:', $request->all());
            
            $returnData = array();
            $LandmarkMasterValidation = new LandmarkMasterValidation();
            $validationResult = $LandmarkMasterValidation->validate($request->all());
            if ($validationResult !== null) {
                return response()->json($validationResult);
            }
            
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "landmark_name" => $request->landmark_name,
                "area_id" => $request->area_id,
                "city_id" => $request->city_id
            ];
            
            // Check if landmark exists excluding soft deleted records
            $existingLandmark = DB::table($this->table)
                ->where('area_id', $request->area_id)
                ->where('city_id', $request->city_id)
                ->where('landmark_name', $request->landmark_name)
                ->whereNull('deleted_at')
                ->when($request->id, function($query, $id) {
                    return $query->where('id', '!=', $id);
                })
                ->first();

            Log::info('Existing Landmark Check:', ['exists' => !is_null($existingLandmark)]);

            if ($existingLandmark) {
                $returnData = [
                    "status" => "exist",
                    "message" => "Landmark already exists in this area and city!",
                    "unique_field" => $uniqueFieldValue
                ];
                Log::warning('Landmark exists:', $returnData);
                
                if ($request->ajax()) {
                    return response()->json($returnData);
                }
                return redirect()->back()->withInput()->with('status', 'exist')->with('message', $returnData['message']);
            }
            
            $objLandmarkMasterModel = new LandmarkMasterModel();
            $post = $request->all();
            
            Log::info('Data to save:', $post);
            
            $returnData = $objLandmarkMasterModel->saveData($post);

            Log::info('Save result:', $returnData);

            // If AJAX call, return JSON
            if ($request->ajax()) {
                return response()->json($returnData);
            }

            // Non-AJAX: redirect with flash
            if (isset($returnData['status']) && $returnData['status'] == 'success') {
                return redirect()->route('landmark.index')->with('status', 'success')->with('message', $returnData['message']);
            } else {
                return redirect()->back()->withInput()->with('status', 'warning')->with('message', $returnData['message'] ?? 'Something went wrong');
            }
        }
        catch(\Throwable $e){
            Log::error('Landmark Save Error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json(['status' => 'warning', 'message' => $e->getMessage()]);
            }
            return redirect()->back()->withInput()->with('status', 'warning')->with('message', $e->getMessage());
        }
    }

    function updateStatus($status, $id){
        try{
            $id = (int) $id;
            $status = (int)$status === 0 ? 1 : 0;
            $data = array('status' => $status , 'id' => $id);
            $objLandmarkMasterModel = new LandmarkMasterModel();
            $returnData = $objLandmarkMasterModel->saveData($data);
            return response()->json($returnData);
        }catch(\Throwable $e){
            Log::error('Landmark status update error: ' . $e->getMessage());
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return response()->json($returnData, 500);
        }
    }
    
  function getWardsByCity(Request $request){
    try{
        $city_id = $request->city_id;
        if(!$city_id) {
            return response()->json([], 200);
        }
        
        $wards = DB::table('wards')
            ->where('status', 0)
            ->where('city_id', $city_id)
            ->whereNull('deleted_at')
            ->select('id', 'ward_name')
            ->orderBy('ward_name', 'asc')
            ->get();
            
        return response()->json($wards, 200);
    }catch(\Throwable $e){
        Log::error('Get wards error: ' . $e->getMessage());
        return response()->json([], 200);
    }
}
    
    function dataDownload(Request $request) {
        return response()->json(['status' => 'success', 'message' => 'Not implemented']);
    }
    
    public function destroy(Request $request, $id)
    {
        try {
            Log::info('Landmark delete request received:', [
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

            // check exists and not already deleted
            $objModel = new LandmarkMasterModel();
            $row = $objModel->getSingleData($id);
            if (!$row) {
                Log::warning('Landmark not found for delete:', ['id' => $id]);
                $resp = ['status' => 'warning', 'message' => 'Landmark not found or already deleted'];
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json($resp, 404);
                }
                return redirect()->back()->with('status', 'warning')->with('message', $resp['message']);
            }

            Log::info('Deleting landmark:', ['id' => $id, 'landmark_name' => $row['landmark_name']]);

            // Perform soft delete: set deleted_at and deleted_by
            $deletedBy = Session::get('id') ?? null;
            $result = DB::table('landmarks')->where('id', $id)->update([
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => $deletedBy
            ]);

            Log::info('Delete result:', ['affected_rows' => $result, 'id' => $id]);

            $resp = ['status' => 'success', 'message' => 'Landmark deleted successfully', 'id' => $id];

            // Always return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($resp);
            }

            // Only redirect for non-AJAX requests
            return redirect()->route('landmark.index')->with('status', 'success')->with('message', $resp['message']);
        } catch (\Throwable $e) {
            Log::error('Landmark delete error:', [
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
}