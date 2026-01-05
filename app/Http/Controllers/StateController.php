<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Session;
use App\Models\CommonModel;
use Illuminate\Http\Request;
use App\Models\StateMasterModel;
use App\validations\StateMasterValidation;
use Illuminate\Routing\Controller as BaseController;

class StateController extends Controller
{
    protected $table = 'states';

    public function index()
    {
        try {
            $data['title'] = "State || HAQHAI";
            $param = [
                'start' => 0,
                'limit' => 10,
            ];
            $lists = StateMasterModel::getAllStateMasterDetails($param);
            $data['lists'] = [];
            $data['total_count'] = 0;
            if ($lists['total_count'] > 0) {
                $data['lists'] = $lists['data'];
                $data['total_count'] = $lists['total_count'];
            }
            return view('state.index', $data);
        } catch (\Throwable $e) {
            $returnData = ['status' => 'warning', 'message' => $e->getMessage()];
            return response()->json($returnData, 500);
        }
    }

    public function getFiltering(Request $request)
    {
        try {
            $data = [];
            $param = [];
            $param['start'] = (int) ($request->start ?? 0);
            $param['limit'] = (int) ($request->limit ?? 10);
            $param['state_name'] = $request->state_name ?? null;
            $param['status'] = isset($request->status) ? $request->status : null;

            $lists = StateMasterModel::getAllStateMasterDetails($param);
            $data['total_count'] = $lists['total_count'];
            $data['lists'] = [];
            $data['message'] = "No record found!";
            $data['status'] = 'empty';
            
            if ($lists['total_count'] > 0) {
                $data['lists'] = $lists['data'];
                $data['status'] = 'success';
                
                // Calculate showing range
                $startItem = $param['start'] + 1;
                $endItem = min($param['start'] + count($data['lists']), $lists['total_count']);
                $data['showing'] = "Showing $startItem to $endItem of " . $lists['total_count'] . " records.";
                
                // Add serial numbers for each row
                foreach ($data['lists'] as $index => $list) {
                    $list->serial_no = $startItem + $index;
                }
            }
            
            return response()->json($data);
        } catch (\Throwable $e) {
            $returnData = ['status' => 'warning', 'message' => $e->getMessage()];
            return response()->json($returnData, 500);
        }
    }

    public function add(Request $request, $id = null)
    {
        try {
            $data['title'] = "State - Add || HAQHAI";
            if ($id != null) {
                $data['id'] = $id;
                $objStateMasterModel = new StateMasterModel();
                $data['singleData'] = $objStateMasterModel->getSingleData($id);
            } else {
                $data['singleData'] = [];
            }
            return view('state.add', $data);
        } catch (\Throwable $e) {
            $returnData = ['status' => 'warning', 'message' => $e->getMessage()];
            return response()->json($returnData, 500);
        }
    }

    public function view($id)
    {
        try {
            $data['title'] = "State - View || HAQHAI";
            $param = ['id' => $id];
            $viewLists = StateMasterModel::getAllStateMasterDetails($param);
            $data['views'] = $viewLists['data'][0] ?? null;
            return view('state.view', $data);
        } catch (\Throwable $e) {
            $returnData = ['status' => 'warning', 'message' => $e->getMessage()];
            return response()->json($returnData, 500);
        }
    }

    public function save(Request $request)
    {
        try {
            \Log::info('State Save Request:', $request->all());
            
            $objCommon = new CommonModel();
            $uniqueFieldValue = [
                "state_name" => $request->state_name,
            ];

            // Check if state name exists excluding soft deleted records
            $existingState = DB::table($this->table)
                ->where('state_name', $request->state_name)
                ->whereNull('deleted_at')
                ->when($request->id, function($query, $id) {
                    return $query->where('id', '!=', $id);
                })
                ->first();

            \Log::info('Existing State Check:', ['exists' => !is_null($existingState)]);

            if ($existingState) {
                $returnData = [
                    "status" => "exist",
                    "message" => "State already exists!",
                    "unique_field" => $uniqueFieldValue
                ];
                \Log::warning('State exists:', $returnData);
                
                if ($request->ajax()) {
                    return response()->json($returnData);
                }
                return redirect()->back()->withInput()->with('status', 'exist')->with('message', $returnData['message']);
            }

            $objStateMasterModel = new StateMasterModel();
            $post = $request->all();
            
            \Log::info('Data to save:', $post);
            
            $returnData = $objStateMasterModel->saveData($post);

            \Log::info('Save result:', $returnData);

            // If AJAX call, return JSON
            if ($request->ajax()) {
                return response()->json($returnData);
            }

            // Non-AJAX: redirect with flash
            if (isset($returnData['status']) && $returnData['status'] == 'success') {
                return redirect()->route('state.index')->with('status', 'success')->with('message', $returnData['message']);
            } else {
                return redirect()->back()->withInput()->with('status', 'warning')->with('message', $returnData['message'] ?? 'Something went wrong');
            }
        } catch (\Throwable $e) {
            \Log::error('State Save Error:', [
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

    public function updateStatus($status, $id)
    {
        try {
            $id = (int) $id;
            // The UI sends current status; toggle it.
            $status = ((int)$status === 0) ? 1 : 0;
            $data = ['status' => $status, 'id' => $id];
            $objStateMasterModel = new StateMasterModel;
            $returnData = $objStateMasterModel->saveData($data);

            // Return JSON always (toggle usually done via AJAX)
            return response()->json($returnData);
        } catch (\Throwable $e) {
            $returnData = ['status' => 'warning', 'message' => $e->getMessage()];
            return response()->json($returnData, 500);
        }
    }

    public function dataDownload(Request $request)
    {
        // Implement as required
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
        $objModel = new StateMasterModel();
        $row = $objModel->getSingleData($id);
        if (!$row) {
            \Log::warning('Record not found for delete:', ['id' => $id]);
            $resp = ['status' => 'warning', 'message' => 'Record not found or already deleted'];
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($resp, 404);
            }
            return redirect()->back()->with('status', 'warning')->with('message', $resp['message']);
        }

        \Log::info('Deleting state:', ['id' => $id, 'state_name' => $row['state_name']]);

        // Perform soft delete: set deleted_at and deleted_by
        $deletedBy = Session::get('id') ?? null;
        $result = DB::table('states')->where('id', $id)->update([
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $deletedBy
        ]);

        \Log::info('Delete result:', ['affected_rows' => $result, 'id' => $id]);

        $resp = ['status' => 'success', 'message' => 'State deleted successfully', 'id' => $id];

        // Always return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($resp);
        }

        // Only redirect for non-AJAX requests
        return redirect()->route('state.index')->with('status', 'success')->with('message', $resp['message']);
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