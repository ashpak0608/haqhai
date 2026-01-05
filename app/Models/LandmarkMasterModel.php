<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;

class LandmarkMasterModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'landmarks';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'landmark_name', 'area_id', 'city_id', 'ward_id', 'status', 
        'created_by', 'created_at', 'updated_by', 'updated_at', 'deleted_by', 'deleted_at'
    ];

    public function getSaveData() {
        return [
            'id', 'landmark_name', 'area_id', 'city_id', 'ward_id', 'status', 
            'created_by', 'created_at', 'updated_by', 'updated_at', 'deleted_by', 'deleted_at'
        ];
    }

    public function saveData($post) {
        $saveFields = $this->getSaveData();
        $finalData = [];
        
        foreach ($post as $k => $v) {
            if (in_array($k, $saveFields)) {
                $finalData[$k] = $v;
            }
        }

        Log::info('Final data for save:', $finalData);

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

                Log::info('Creating new landmark:', $finalData);

                $model = new LandmarkMasterModel();
                foreach ($finalData as $k => $v) {
                    $model->$k = $v;
                }
                $model->save();
                $id = $model->id;
                
                Log::info('New landmark created with ID:', ['id' => $id]);
                
                return ['id' => $id, 'status' => 'success', 'message' => "Landmark saved successfully!"];
            } else {
                // Update existing record
                $existing = $this->getSingleData($id);
                if ($existing) {
                    $finalData['updated_at'] = date("Y-m-d H:i:s");
                    $finalData['updated_by'] = Session::get('id') ?? 1;

                    Log::info('Updating landmark:', ['id' => $id, 'data' => $finalData]);

                    DB::table($this->table)->where('id', $id)->update($finalData);
                    return ['id' => $id, 'status' => 'success', 'message' => "Landmark updated successfully!"];
                } else {
                    Log::warning('Landmark not found for update:', ['id' => $id]);
                    return ['status' => 'warning', 'message' => 'Record not found'];
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in saveData:', [
                'message' => $e->getMessage(),
                'data' => $finalData,
                'id' => $id
            ]);
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function getSingleData($id) {
        $id = (int) $id;
        // Only exclude actually deleted records
        $result = DB::select("SELECT l.* FROM " . $this->table . " as l WHERE l.id = ? AND (l.deleted_at IS NULL)", [$id]);
        foreach ($result as $data) {
            return json_decode(json_encode($data), true);
        }
        return false;
    }

    static function details($param = []){
        $query = DB::table('landmarks as l')
            ->leftJoin('users as u', 'l.created_by', '=', 'u.id')
            ->leftJoin('users as u1', 'l.updated_by', '=', 'u1.id')
            ->join('areas as a', 'l.area_id', '=', 'a.id')
            ->leftJoin('cities as c', 'l.city_id', '=', 'c.id')
            ->leftJoin('wards as w', 'l.ward_id', '=', 'w.id')
            ->select(DB::raw("
                l.id,
                l.landmark_name,
                l.status,
                a.area_name,
                c.city_name,
                w.ward_name,
                ifnull(u.full_name,'') as created_by,
                ifnull(date_format(l.created_at,'%d-%m-%Y %h:%i %p'),'') as created_at,
                ifnull(u1.full_name,'') as updated_by,
                ifnull(date_format(l.updated_at,'%d-%m-%Y %h:%i %p'),'') as updated_at
            "));
        
        // Only exclude actually deleted records
        $query->whereNull('l.deleted_at');
        
        if(isset($param['status']) && in_array($param['status'],[0,1])){
            $query->where('l.status', $param['status']);
        }
        if(!empty($param['id'])){
            $query->where('l.id', $param['id']);
        }
        if(isset($param['landmark_name']) && !empty($param['landmark_name'])){
            $query->where('l.landmark_name','like','%'.$param['landmark_name'].'%'); 
        }
        if(!empty($param['area_id'])){
            $query->where('l.area_id', $param['area_id']);
        }
        if(!empty($param['city_id'])){
            $query->where('l.city_id', $param['city_id']);
        }
        if(!empty($param['ward_id'])){
            $query->where('l.ward_id', $param['ward_id']);
        }
        
        $total_count = $query->count();
        
        if(isset($param['limit']) && isset($param['start'])){
            $query->limit($param['limit'])->offset($param['start']);
        }
        
        $query->orderBy('l.landmark_name', 'asc');
        $result = $query->get();
        
        if($total_count > 0){
            return array('total_count' => $total_count, 'data' => $result);
        } else {
            return array('total_count' => 0, 'data' => []);
        }
    }
}