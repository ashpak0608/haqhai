<?php

namespace App\Models;

use Session;
use DateTime;
use App\Models\ZFormModel;
use App\Models\ProspectModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommonModel extends Model
{
    use HasFactory;

    public static function getSingle($table,$data){
		$result = DB::table($table)
		->where($data)
		->get();
		if($result){
			return $result;
		}else{
			return false;
		}	
	}

    public static function getSingleWithColumn($table, $where, $columns = ['*'], $orderBy = null, $orderDirection = 'asc'){
        $query = DB::table($table)
                    ->select($columns)
                    ->where($where);

        if ($orderBy) {
            $query->orderBy($orderBy, $orderDirection);
        }

        $result = $query->get();

        return $result->isNotEmpty() ? $result : collect();
    }
    public static function getSingleGroupConcat($table, $data, $column){
        $result = DB::table($table)
            ->selectRaw("GROUP_CONCAT($column) as concatenated_column")
            ->where($data) 
            ->first();
    
        if ($result) {
            return $result->concatenated_column;
        } else {
            return false;
        }
    } 

    public static function getSingleOrderBy($table, $data, $orderBy = null, $orderDirection = 'asc'){
        $query = DB::table($table)->where($data);

        if ($orderBy) {
            $query->orderBy($orderBy, $orderDirection);
        }

        $result = $query->get();

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    static function getRecords($records){
        $result = [];
        foreach($records as $record){
            array_push($result,$record->email);
        }
        return $result;
    }

    public static function getDistinctData($table, $column, $where){
        $result = DB::table($table)
            ->selectRaw("Distinct $column")
            ->where($where)
            ->get();

        return $result;
    }

     Public function checkMultiCommaSeparatedUnique($table, $uniqueFieldValue, $id = 0) {
        $bindings = [];
        $query = "SELECT COUNT(*) as total FROM " . $table . " WHERE 1=1 ";
       foreach ($uniqueFieldValue as $key => $values) {
           if (is_array($values)) {
               // If it's an array, use multiple FIND_IN_SET conditions
               $conditions = [];
               foreach ($values as $value) {
                    if($value!=0){
                        $conditions[] = "FIND_IN_SET($value, $key) > 0";
                    }
                }
                    if (!empty($conditions)) {
                        $query .= " AND (" . implode(' OR ', $conditions) . ")";
                    }
           } else {
                if($values!=0){
                   // If it's a single value, use a single FIND_IN_SET condition
                    $query .= " AND FIND_IN_SET($values, $key) > 0";
                }
           }
       }
        // Add the condition to exclude a specific ID
        $id = (int) $id;
            $query .= " AND id != $id";
        // echo $query;
        // exit;
        $resultSet = DB::select(DB::raw($query));
        if (!$resultSet) {
            return false;
        }
        $count = 0;
        foreach ($resultSet as $data) {
            $row = json_decode(json_encode($data), True);
            $count = $row['total'];
        }
        return $count;
    }
    
	Public function checkUnique($table, $uniqueField, $uniqueFieldValue, $id = 0) {
        $query = "SELECT COUNT(*) as total FROM " . $table . " WHERE $uniqueField = '" . $uniqueFieldValue . "'";
        $id = (int) $id;
        if ($id > 0) {
            $query .= " AND id != " . $id . "";
        }
        $resultSet = DB::select(DB::raw($query));
        if (!$resultSet) {
            return false;
        }
        $count = 0;
        foreach ($resultSet as $data) {
            $row = json_decode(json_encode($data), True);
            $count = $row['total'];
        }
        return $count;
    }

    Public function checkINMultiUniqueOrderByDESC($table, $uniqueFieldValue, $id = 0) {
        $query = "SELECT COUNT(*) as total FROM " . $table . " WHERE 1=1 ";
        foreach ($uniqueFieldValue as $key => $value) {
            if (is_array($value)) {
                // Convert the array values to a comma-separated string
                $valuesString = implode("','", $value);
                $query .= " AND $key IN ('$valuesString')";
            } else {
                    $query .= " AND $key = '$value'";
            }
        }
        $id = (int) $id;
        if ($id > 0) {
            $query .= " AND id != " . $id . "";
        }
        $query .= " order by Id DESC";
        $resultSet = DB::select(DB::raw($query));
        if (!$resultSet) {
            return false;
        }
        $count = 0;
        foreach ($resultSet as $data) {
            $row = json_decode(json_encode($data), True);
            $count = $row['total'];
        }
        return $count;
    }
    
    Public function checkMultiUnique($table, $uniqueFieldValue, $id = 0) {
        $query = "SELECT COUNT(*) as total FROM " . $table . " WHERE 1=1 ";
        foreach ($uniqueFieldValue as $key => $value) {
            $query .= " AND $key = '" . $value . "'";
        }
        $id = (int) $id;
        if ($id > 0) {
            $query .= " AND id != " . $id . "";
        }
        $resultSet = DB::select($query);
        if (!$resultSet) {
            return false;
        }
        $count = 0;
        foreach ($resultSet as $data) {
            $row = json_decode(json_encode($data), True);
            $count = $row['total'];
        }
        return $count;
    }

    Public function checkINMultiUnique($table, $uniqueFieldValue, $id = 0) {
        $query = "SELECT COUNT(*) as total FROM " . $table . " WHERE 1=1 ";
        foreach ($uniqueFieldValue as $key => $value) {
            $query .= " AND $key in ('" . $value . "')";
        }
        $id = (int) $id;
        if ($id > 0) {
            $query .= " AND id != " . $id . "";
        }
        $resultSet = DB::select($query);
        if (!$resultSet) {
            return false;
        }
        $count = 0;
        foreach ($resultSet as $data) {
            $row = json_decode(json_encode($data), True);
            $count = $row['total'];
        }
        return $count;
    }

    public static function simpleUpdate($table,$condition,$data){

        $result = DB::table($table)
        ->where($condition)
        ->update($data);
        if($result){
            return $result;
        }else{
            return false;
        }   
    }
    
    static function getDateDifference($date1,$date2){
        $result = DB::select("select DATEDIFF('$date1', '$date2') as getDate");
        foreach ($result as $data) {
            return json_decode(json_encode($data), True);
        }
        return false;
    }

    static function validateDate($date){
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    static function countData($table, $condition){
        return DB::table($table)->where($condition)->count() ?? 0;
    }

    static function propertyAge() {
        $propertyAges = [
            [0, 1],
            [1, 3],
            [3, 5],
            [5, 10],
            [10, 15],
            [15, 20],
            [20, 25],
            [25, 30],
            [30, 35],
            [35, 40],
            [40, 45],
            [45, 50]
        ];
    
        // Format the range as "0–1 yrs", "1–3 yrs", etc.
        $formattedAges = [];
        foreach ($propertyAges as $range) {
            $formattedAges[] = "{$range[0]}–{$range[1]} yrs";
        }
    
        return $formattedAges;
    }
    
    static function getPropertyFacing(){
        return [
            'North',
            'South',
            'East',
            'West',
            'North-East',
            'North-West',
            'South-East',
            'South-West'
        ];
    }
    static function getLoanAvailability(){
        return [
            'Yes',
            'No'
        ];
    }
}