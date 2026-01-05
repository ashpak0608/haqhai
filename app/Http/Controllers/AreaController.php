<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\LocationMasterModel;
use App\Models\AreaModel;
use App\validations\AreaMasterValidation;
use Illuminate\Routing\Controller as BaseController;

class AreaController extends Controller {

    protected $table = 'areas';
    
    function index() {
        try{
            $data['title'] = "Area || HAQHAI";
            
            // Get only non-deleted locations
            $data['locations'] = DB::table('locations')
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->get();
                
            $param=array(
                'start' => 0,
                'limit' => 10,
            );
            $lists = AreaModel::details($param);
            $data['lists'] = array();
            $data['total_count'] = 0;
            if($lists['total_count'] > 0){
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('area.index',$data);
        }
        catch(\Throwable $e){
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
            $param['area_name'] = $request->area_name ?? null;
            $param['location_id'] = $request->location_id ?? null;
            $param['status'] = $request->status ?? null;
            
            $lists = AreaModel::details($param);
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
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return response()->json($returnData, 500);
        }
    }

    function add(Request $request , $id=null) { 
        try{
            $data['title'] = "Area - Add || HAQHAI";
            if($id != null) {
                $data['id'] = $id;
                $objAreaModel = new AreaModel();
                $data['singleData'] = $objAreaModel->getSingleData($id);
            }
            else {
                $data['singleData'] = array();
            }
            
            // Get only non-deleted locations
            $data['locations'] = DB::table('locations')
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->get();
                
            return view('area.add',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return response()->json($returnData, 500);
        }
    }

    function view($id) {
        try{
            $data['title'] = "Area - View || HAQHAI";
            $param = array('id' => $id);
            $viewLists = AreaModel::details($param);
            $data['views'] = $viewLists['data'][0] ?? null;
            return view('area.view',$data);
        }
        catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return response()->json($returnData, 500);
        }
    }

    function save(Request $request) {
        try{
            \Log::info('Area Save Request:', $request->all());
            
            $returnData = array();
            $AreaMasterValidation = new AreaMasterValidation();
            $validationResult = $AreaMasterValidation->validate($request->all());
            if ($validationResult !== null) {
                return response()->json($validationResult);
            }
            
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "area_name" => $request->area_name,
                "location_id" => $request->location_id
            ];
            
            // Check if area exists excluding soft deleted records
            $existingArea = DB::table($this->table)
                ->where('location_id', $request->location_id)
                ->where('area_name', $request->area_name)
                ->whereNull('deleted_at')
                ->when($request->id, function($query, $id) {
                    return $query->where('id', '!=', $id);
                })
                ->first();

            \Log::info('Existing Area Check:', ['exists' => !is_null($existingArea)]);

            if ($existingArea) {
                $returnData = [
                    "status" => "exist",
                    "message" => "Area already exists in this location!",
                    "unique_field" => $uniqueFieldValue
                ];
                \Log::warning('Area exists:', $returnData);
                
                if ($request->ajax()) {
                    return response()->json($returnData);
                }
                return redirect()->back()->withInput()->with('status', 'exist')->with('message', $returnData['message']);
            }
            
            $objAreaModel = new AreaModel();
            $post = $request->all();
            
            \Log::info('Data to save:', $post);
            
            $returnData = $objAreaModel->saveData($post);

            \Log::info('Save result:', $returnData);

            // If AJAX call, return JSON
            if ($request->ajax()) {
                return response()->json($returnData);
            }

            // Non-AJAX: redirect with flash
            if (isset($returnData['status']) && $returnData['status'] == 'success') {
                return redirect()->route('areas.index')->with('status', 'success')->with('message', $returnData['message']);
            } else {
                return redirect()->back()->withInput()->with('status', 'warning')->with('message', $returnData['message'] ?? 'Something went wrong');
            }
        }
        catch(\Throwable $e){
            \Log::error('Area Save Error:', [
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
            $objAreaModel = new AreaModel;
            $returnData = $objAreaModel->saveData($data);
            return response()->json($returnData);
        }catch(\Throwable $e){
            $returnData = array('status' => 'warning', 'message' => $e->getMessage());
            return response()->json($returnData, 500);
        }
    }
    
    function dataDownload(Request $request) {
        return response()->json(['status' => 'success', 'message' => 'Not implemented']);
    }
    
    public function destroy(Request $request, $id)
    {
        try {
            \Log::info('Delete request received:', [
                'id' => $id,
                'method' => $request->method(),
                'ajax' => $request->ajax(),
                'wantsJson' => $request->wantsJson(),
                'all_data' => $request->all()
            ]);

            $id = (int) $id;
            if ($id <= 0) {
                \Log::warning('Invalid ID for delete:', ['id' => $id]);
                $resp = ['status' => 'warning', 'message' => 'Invalid ID'];
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json($resp, 400);
                }
                return redirect()->back()->with('status', 'warning')->with('message', 'Invalid ID');
            }

            // check exists and not already deleted
            $objModel = new AreaModel();
            $row = $objModel->getSingleData($id);
            if (!$row) {
                \Log::warning('Record not found for delete:', ['id' => $id]);
                $resp = ['status' => 'warning', 'message' => 'Record not found or already deleted'];
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json($resp, 404);
                }
                return redirect()->back()->with('status', 'warning')->with('message', $resp['message']);
            }

            \Log::info('Deleting area:', ['id' => $id, 'area_name' => $row['area_name']]);

            // Perform soft delete: set deleted_at and deleted_by
            $deletedBy = Session::get('id') ?? null;
            $result = DB::table('areas')->where('id', $id)->update([
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => $deletedBy
            ]);

            \Log::info('Delete result:', ['affected_rows' => $result, 'id' => $id]);

            $resp = ['status' => 'success', 'message' => 'Area deleted successfully', 'id' => $id];

            // Always return JSON for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($resp);
            }

            // Only redirect for non-AJAX requests
            return redirect()->route('areas.index')->with('status', 'success')->with('message', $resp['message']);
        } catch (\Throwable $e) {
            \Log::error('Delete error:', [
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