<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuildingModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'buildings';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'state_id', 'city_id', 'ward_id', 'landmark_id', 'building_name',
        'latitude', 'longitude', 'status', 'created_by', 'created_at',
        'updated_by', 'updated_at', 'deleted_by', 'deleted_at'
    ];

    // Set default attribute values
    protected $attributes = [
        'status' => '0' // Active by default
    ];

    public function getSaveData()
    {
        return [
            'id', 'state_id', 'city_id', 'ward_id', 'landmark_id', 'building_name',
            'latitude', 'longitude', 'status', 'created_by', 'created_at',
            'updated_by', 'updated_at', 'deleted_by', 'deleted_at'
        ];
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

        // Convert empty strings to null for latitude/longitude
        if (isset($finalData['latitude']) && $finalData['latitude'] === '') {
            $finalData['latitude'] = null;
        }
        if (isset($finalData['longitude']) && $finalData['longitude'] === '') {
            $finalData['longitude'] = null;
        }

        // Ensure status is set (default to 0 if not provided)
        if (!isset($finalData['status'])) {
            $finalData['status'] = '0';
        }

        if (isset($finalData['id'])) {
            $id = (int) $finalData['id'];
            unset($finalData['id']);
        } else {
            $id = 0;
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
                $finalData['created_by'] = Session::get('id') ?? 1;
                $finalData['updated_at'] = null;
                $finalData['updated_by'] = null;

                \Log::info('Creating new building:', $finalData);

                $model = new BuildingModel();
                foreach ($finalData as $k => $v) {
                    $model->$k = $v;
                }
                $model->save();
                $id = $model->id;
                
                \Log::info('New building created with ID:', ['id' => $id]);
                
                return ['id' => $id, 'status' => 'success', 'message' => "Building saved successfully!"];
            } else {
                // Update existing record
                $existing = $this->getSingleData($id);
                if ($existing) {
                    $finalData['updated_at'] = date("Y-m-d H:i:s");
                    $finalData['updated_by'] = Session::get('id') ?? 1;

                    \Log::info('Updating building:', ['id' => $id, 'data' => $finalData]);

                    DB::table($this->table)->where('id', $id)->update($finalData);
                    return ['id' => $id, 'status' => 'success', 'message' => "Building updated successfully!"];
                } else {
                    \Log::warning('Building not found for update:', ['id' => $id]);
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
        $id = (int)$id;
        // Only exclude actually deleted records
        $result = DB::select("SELECT b.* FROM " . $this->table . " as b WHERE b.id = ? AND (b.deleted_at IS NULL)", [$id]);
        foreach ($result as $data) {
            return json_decode(json_encode($data), true);
        }
        return false;
    }

    static function getAllbuildingDetails($param = [])
    {
        $query = DB::table('buildings as c');

        // user info
        $query->leftjoin('users as u', 'c.created_by', '=', 'u.id');
        $query->leftjoin('users as u1', 'c.updated_by', '=', 'u1.id');

        // location joins
        $query->join('cities as d', 'c.city_id', '=', 'd.id');
        $query->join('states as s', 'c.state_id', '=', 's.id');
        $query->leftjoin('wards as w', 'c.ward_id', '=', 'w.id');
        $query->leftjoin('landmarks as l', 'c.landmark_id', '=', 'l.id');

        $query->select(DB::raw("
            c.id,
            c.building_name,
            c.latitude,
            c.longitude,
            c.state_id,
            c.city_id,
            c.ward_id,
            c.landmark_id,
            c.status,
            s.state_name,
            d.city_name,
            w.ward_name,
            ifnull(l.landmark_name,'') as landmark_name,
            ifnull(u.full_name,'') as created_by,
            ifnull(date_format(c.created_at,'%d-%m-%Y %h:%i %p'),'') as created_at,
            ifnull(u1.full_name,'') as updated_by,
            ifnull(date_format(c.updated_at,'%d-%m-%Y %h:%i %p'),'') as updated_at
        "));

        // Only exclude actually deleted records
        $query->whereNull('c.deleted_at');

        if (isset($param['status']) && (in_array($param['status'], [0, 1]))) {
            $query->where('c.status', $param['status']);
        }
        if (!empty($param['id'])) {
            $query->where('c.id', $param['id']);
        }
        
        // Building name search - works independently of other filters
        if (isset($param['building_name']) && !empty(trim($param['building_name']))) {
            $buildingName = trim($param['building_name']);
            $query->where('c.building_name', 'like', '%' . $buildingName . '%');
        }
        
        // Other filters - these are optional and work independently
        if (!empty($param['city_id'])) {
            $query->where('c.city_id', $param['city_id']);
        }
        if (!empty($param['state_id'])) {
            $query->where('c.state_id', $param['state_id']);
        }
        if (!empty($param['ward_id'])) {
            $query->where('c.ward_id', $param['ward_id']);
        }
        if (!empty($param['landmark_id'])) {
            $query->where('c.landmark_id', $param['landmark_id']);
        }

        $total_count = $query->count();

        if (isset($param['limit']) && isset($param['start'])) {
            $query->limit($param['limit'])->offset($param['start']);
        }
        $query->orderBy('c.building_name', 'asc');
        $result = $query->get();

        if ($total_count > 0) {
            return ['total_count' => $total_count, 'data' => $result];
        } else {
            return ['total_count' => 0, 'data' => []];
        }
    }

    // Relationship with Ward
    public function ward()
    {
        return $this->belongsTo(WardModel::class, 'ward_id');
    }

    // Relationship with Google Maps drawings
    public function mapDrawings()
    {
        return $this->morphMany(GoogleMap::class, 'module', 'module_name', 'module_id');
    }

    public function getDefaultMapDrawing()
    {
        return $this->mapDrawings()
                    ->where('is_default', true)
                    ->where('status', 'active')
                    ->first();
    }
}