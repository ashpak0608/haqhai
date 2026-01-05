<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class StateMasterModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'states';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'state_name', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at', 'deleted_by', 'deleted_at'
    ];

    public function getSaveData()
    {
        return ['id', 'state_name', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at', 'deleted_by', 'deleted_at'];
    }

   public function saveData($post)
{
    $saveFields = $this->getSaveData();

    $finalData = [];
    foreach ($post as $k => $v) {
        if (in_array($k, $saveFields)) {
            $finalData[$k] = $v;
        }
    }

    \Log::info('Final data for save:', $finalData);

    $id = 0;
    if (isset($finalData['id']) && !empty($finalData['id'])) {
        $id = (int) $finalData['id'];
        unset($finalData['id']);
    }

    try {
        if ($id === 0) {
            // Create new record
            if (!isset($finalData['status']) || $finalData['status'] === '' || $finalData['status'] === null) {
                $finalData['status'] = 0;
            } else {
                $finalData['status'] = (int)$finalData['status'];
            }
            $finalData['created_at'] = date("Y-m-d H:i:s");
            $finalData['created_by'] = Session::get('id') ?? 1; // Default to 1 if session not available
            $finalData['updated_at'] = null;
            $finalData['updated_by'] = null;

            \Log::info('Creating new state:', $finalData);

            $model = new StateMasterModel();
            foreach ($finalData as $k => $v) {
                $model->$k = $v;
            }
            $model->save();
            $id = $model->id;
            
            \Log::info('New state created with ID:', ['id' => $id]);
            
            return ['id' => $id, 'status' => 'success', 'message' => "State saved successfully!"];
        } else {
            // Update existing record
            $existing = $this->getSingleData($id);
            if ($existing) {
                $finalData['updated_at'] = date("Y-m-d H:i:s");
                $finalData['updated_by'] = Session::get('id') ?? 1;

                \Log::info('Updating state:', ['id' => $id, 'data' => $finalData]);

                DB::table($this->table)->where('id', $id)->update($finalData);
                return ['id' => $id, 'status' => 'success', 'message' => "State updated successfully!"];
            } else {
                \Log::warning('State not found for update:', ['id' => $id]);
                return ['status' => 'warning', 'message' => 'Record not found'];
            }
        }
    } catch (\Exception $e) {
        \Log::error('Error in saveData:', [
            'message' => $e->getMessage(),
            'data' => $finalData,
            'id' => $id
        ]);
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}

    public function getSingleData($id)
    {
        $id = (int) $id;
        // Exclude soft deleted rows
       $result = DB::select("SELECT c.* FROM " . $this->table . " as c WHERE c.id = ? AND (c.deleted_at IS NULL OR c.deleted_at = '0000-00-00 00:00:00')", [$id]);
        foreach ($result as $data) {
            return json_decode(json_encode($data), true);
        }
        return false;
    }

    public static function getAllStateMasterDetails($param = [])
    {
        $query = DB::table('states as c');
        $query->leftJoin('users as u', 'c.created_by', '=', 'u.id');
        $query->leftJoin('users as u1', 'c.updated_by', '=', 'u1.id');
        $query->select(DB::raw("
            c.id,
            c.state_name,
            c.status,
            ifnull(u.full_name,'') as created_by,
            ifnull(date_format(c.created_at,'%d-%m-%Y %h:%i %p'),'') as created_at,
            ifnull(u1.full_name,'') as updated_by,
            ifnull(date_format(c.updated_at,'%d-%m-%Y %h:%i %p'),'') as updated_at
        "));

        // exclude soft deleted
       $query->where(function($q){     $q->whereNull('c.deleted_at')       ->orWhere('c.deleted_at', '=', '0000-00-00 00:00:00'); });

        if (isset($param['status']) && ($param['status'] === '0' || $param['status'] === '1' || $param['status'] === 0 || $param['status'] === 1)) {
            $query->where('c.status', $param['status']);
        }
        if (isset($param['id']) && !empty($param['id'])) {
            $query->where('c.id', $param['id']);
        }
        if (isset($param['state_name']) && !empty($param['state_name'])) {
            $query->where('c.state_name', 'like', '%' . $param['state_name'] . '%');
        }

        $total_count = $query->count();

        if (isset($param['limit']) && isset($param['start'])) {
            $query->limit((int)$param['limit'])->offset((int)$param['start']);
        }

        $query->orderBy('c.id', 'desc');
        $result = $query->get();

        if ($total_count > 0) {
            return ['total_count' => $total_count, 'data' => $result];
        } else {
            return ['total_count' => 0, 'data' => []];
        }
    }
}
