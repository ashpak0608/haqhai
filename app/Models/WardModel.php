<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WardModel extends Model
{
    use HasFactory;

    protected $table = 'wards';

    protected $fillable = [
        'id','city_id', 'ward_name', 'ward_number','status', 'created_by', 'created_at', 'updated_by', 'updated_at',
        'boundary_data', 'center_lat', 'center_lng' // Added for hierarchical maps
    ];

    protected $casts = [
        'center_lat' => 'decimal:8',
        'center_lng' => 'decimal:8',
        'boundary_data' => 'array'
    ];

    public function getSaveData() {
        return array(
            'id','city_id', 'ward_name', 'ward_number','status', 'created_by', 'created_at', 'updated_by', 'updated_at',
            'boundary_data', 'center_lat', 'center_lng'
        );
    }

    public function saveData($post) {
        $saveFields = $this->getSaveData();
        $finalData = new WardModel;
        foreach ($post as $k => $v) {
            if (in_array($k, $saveFields)) {
                $finalData[$k] = $v;
            }
        }
        
        // Convert empty strings to null for coordinates
        if (isset($finalData['center_lat']) && $finalData['center_lat'] === '') {
            $finalData['center_lat'] = null;
        }
        if (isset($finalData['center_lng']) && $finalData['center_lng'] === '') {
            $finalData['center_lng'] = null;
        }

        if (isset($finalData['id'])) {
            $id = (int) $finalData['id'];
        } else {
            $id = 0;
            unset($finalData['id']);
        }

        if ($id == 0) {
            $finalData['created_at'] = date("Y-m-d H:i:s");
            $finalData['created_by'] = Session::get('id');
            $finalData['updated_at'] = null;
            $finalData->save();
            $id = $finalData->id;
            return array('id' => $id, 'status' => 'success', 'message' => "Ward Data saved!");
        } else {
            if ($this->getSingleData($id)) {
                $finalData['updated_at'] = date("Y-m-d H:i:s");
                $finalData['updated_by'] = Session::get('id');
                $finalData->exists = true;
                $finalData->id = $id;
                $finalData->save();
                return array('id' => $id, 'status' => 'success', 'message' => "Ward Data updated!");
            } else {
                return false;
            }
        }
    }

    public function getSingleData($id) {
        $id = (int) $id;
        $result = DB::select("SELECT c.* FROM " . $this->table . " as c WHERE c.id=$id");
        foreach ($result as $data) {
            return json_decode(json_encode($data), True);
        }

        return false;
    }

    static function getAllWardDetails($param = []){
       $query = DB::table('wards as c');
       $query->leftjoin('users as u','c.created_by','=','u.id');
       $query->leftjoin('users as u1','c.updated_by','=','u1.id');
       $query->join('cities as d','c.city_id','=','d.id');
       $query->select(DB::raw("
        c.id,
        c.ward_name,
        c.ward_number,
        c.city_id,
        c.status,
        c.boundary_data,
        c.center_lat,
        c.center_lng,
        d.city_name,
        ifnull(u.full_name,'') as created_by,
        ifnull(date_format(c.created_at,'%d-%m-%Y %h:%m %p'),'') as created_at,
        ifnull(u1.full_name,'') as updated_by,
        ifnull(date_format(c.updated_at,'%d-%m-%Y %h:%m %p'),'') as updated_at"));
        if(isset($param['status']) && (in_array($param['status'],[0,1]))){
            $query->where('c.status',$param['status']);
        }
        if(!empty($param['id'])){
            $query->where('c.id',$param['id']);
        }
        if(isset($param['ward_name']) && !empty($param['ward_name'])){
            $query->where('c.ward_name','like','%'.$param['ward_name'].'%'); 
        }
        if(!empty($param['city_id'])){
            $query->where('c.city_id',$param['city_id']);
        }
        $total_count = $query->count();
        if(isset($param['limit']) && isset($param['start'])){
            $query->limit($param['limit'])->offset($param['start']);
        }
        $query->orderBy('c.ward_name','asc');
        $result = $query->get();
        if($total_count > 0){
            return array('total_count'=>$total_count,'data'=>$result);
        }else{
            return array('total_count'=>0,'data'=>[]);
        }
    }

    // Relationship with Buildings
    public function buildings()
    {
        return $this->hasMany(BuildingModel::class, 'ward_id');
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

    public function hasBoundary()
    {
        return !empty($this->boundary_data);
    }

    // Get city for this ward
    public function city()
    {
        return $this->belongsTo(CityModel::class, 'city_id');
    }
}