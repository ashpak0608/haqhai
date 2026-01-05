<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationMasterModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'locations';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'location_name', 'city_id', 'status', 
        'created_by', 'created_at', 'updated_by', 'updated_at', 'deleted_by', 'deleted_at'
    ];

    public function getSaveData() {
        return [
            'id', 'location_name', 'city_id', 'status', 
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

        \Log::info('Final data for save:', $finalData);

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

                \Log::info('Creating new location:', $finalData);

                $model = new LocationMasterModel();
                foreach ($finalData as $k => $v) {
                    $model->$k = $v;
                }
                $model->save();
                $id = $model->id;
                
                \Log::info('New location created with ID:', ['id' => $id]);
                
                return ['id' => $id, 'status' => 'success', 'message' => "Location saved successfully!"];
            } else {
                // Update existing record
                $existing = $this->getSingleData($id);
                if ($existing) {
                    $finalData['updated_at'] = date("Y-m-d H:i:s");
                    $finalData['updated_by'] = Session::get('id') ?? 1;

                    \Log::info('Updating location:', ['id' => $id, 'data' => $finalData]);

                    DB::table($this->table)->where('id', $id)->update($finalData);
                    return ['id' => $id, 'status' => 'success', 'message' => "Location updated successfully!"];
                } else {
                    \Log::warning('Location not found for update:', ['id' => $id]);
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

    public function getSingleData($id) {
        $id = (int) $id;
        // Only exclude actually deleted records
        $result = DB::select("SELECT c.* FROM " . $this->table . " as c WHERE c.id = ? AND (c.deleted_at IS NULL)", [$id]);
        foreach ($result as $data) {
            return json_decode(json_encode($data), true);
        }
        return false;
    }

    static function getAllPinLocationDetails($param = []){
       $query = DB::table('locations as c');
       $query->leftjoin('users as u','c.created_by','=','u.id');
       $query->leftjoin('users as u1','c.updated_by','=','u1.id');
       $query->join('cities as d','c.city_id','=','d.id');
       $query->select(DB::raw("
        c.id,
        c.location_name,
        c.city_id,
        c.status,
        d.city_name,
        ifnull(u.full_name,'') as created_by,
        ifnull(date_format(c.created_at,'%d-%m-%Y %h:%i %p'),'') as created_at,
        ifnull(u1.full_name,'') as updated_by,
        ifnull(date_format(c.updated_at,'%d-%m-%Y %h:%i %p'),'') as updated_at"));
        
        // Only exclude actually deleted records
        $query->whereNull('c.deleted_at');
        
        if(isset($param['status']) && (in_array($param['status'],[0,1]))){
            $query->where('c.status',$param['status']);
        }
        if(!empty($param['id'])){
            $query->where('c.id',$param['id']);
        }
        if(isset($param['location_name']) && !empty($param['location_name'])){
            $query->where('c.location_name','like','%'.$param['location_name'].'%'); 
        }
        if(!empty($param['city_id'])){
            $query->where('c.city_id',$param['city_id']);
        }
        $total_count = $query->count();
        if(isset($param['limit']) && isset($param['start'])){
            $query->limit($param['limit'])->offset($param['start']);
        }
        $query->orderBy('c.location_name','asc');
        $result = $query->get();
        if($total_count > 0){
            return array('total_count'=>$total_count,'data'=>$result);
        }else{
            return array('total_count'=>0,'data'=>[]);
        }
    }
}