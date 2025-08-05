<?php

namespace App\Models;

use DB;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyAreaTypeModel extends Model
{
    use HasFactory;

    protected $table = 'property_area_types';

    protected $fillable = [
        'id', 'area_type', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'
    ];

    public function getSaveData() {
        return array('id', 'area_type', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at');
    }

    public function saveData($post) {
        $saveFields = $this->getSaveData();
        $finalData = new PropertyAreaTypeModel;
        foreach ($post as $k => $v) {
            if (in_array($k, $saveFields)) {
                $finalData[$k] = $v;
            }
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
            return array('id' => $id, 'status' => 'success', 'message' => "Floor Data saved!");
        } else {
            if ($this->getSingleData($id)) {
                $finalData['updated_at'] = date("Y-m-d H:i:s");
                $finalData['updated_by'] = Session::get('id');
                $finalData->exists = true;
                $finalData->id = $id;
                $finalData->save();
                return array('id' => $id, 'status' => 'success', 'message' => "Floor Data updated!");
            } else {
                return false;
            }
        }
    }

    public function getSingleData($id) {
        $id = (int) $id;
        $result = DB::select("SELECT * FROM " . $this->table . " WHERE id = $id");
        foreach ($result as $data) {
            return json_decode(json_encode($data), True);
        }
        return false;
    }

    static function getDetails($param = []){
        $query = DB::table('property_area_types as f');
        $query->leftJoin('users as u', 'f.created_by', '=', 'u.id');
        $query->leftJoin('users as u1', 'f.updated_by', '=', 'u1.id');
        $query->select(DB::raw("
            f.id,
            f.area_type,
            f.status,
            ifnull(u.user_name,'') as created_by,
            ifnull(date_format(f.created_at, '%d-%m-%Y %h:%i %p'),'') as created_at,
            ifnull(u1.user_name,'') as updated_by,
            ifnull(date_format(f.updated_at, '%d-%m-%Y %h:%i %p'),'') as updated_at"));

        if (isset($param['status']) && in_array($param['status'], [0, 1])) {
            $query->where('f.status', $param['status']);
        }
        if (isset($param['id']) && !empty($param['id'])) {
            $query->where('f.id', $param['id']);
        }
        if (isset($param['area_type']) && !empty($param['area_type'])) {
            $query->where('f.area_type', 'like', '%' . $param['area_type'] . '%');
        }

        $total_count = $query->count();
        
        if (isset($param['limit']) && isset($param['start'])) {
            $query->limit($param['limit'])->offset($param['start']);
        }
        
        $query->orderBy('f.id', 'asc');
        $result = $query->get();

        return $total_count > 0 ? ['total_count' => $total_count, 'data' => $result] : ['total_count' => 0, 'data' => []];
    }
}
