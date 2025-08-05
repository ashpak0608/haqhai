<?php

namespace Database\Seeders;

use App\Models\SocietyMasterModel;
use Illuminate\Database\Seeder;

class SocietiesSeeder extends Seeder
{
    public function run()
    {
        $societies = [];
        $developers = [1, 2, 3, 4, 5];
        $propertyTypes = [1, 2, 3, 4, 5, 6];
        $locations = [
                1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 
                11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
                21, 22, 23, 24, 25, 26, 27, 28, 29, 30,
                31, 32, 33, 34, 35, 36, 37
                ];
        
        for ($i = 1; $i <= 10000; $i++) {
            $societies[] = [
                'title' => 'Residency ' . $i,
                'country_id' => 77,
                'city_id' => rand(1, 36),
                'state_id' => 14,
                'address' => 'Sample address ' . $i,
                'location_id' => $locations[array_rand($locations)],
                'property_rara_no' => 'RERA' . rand(1000, 9999),
                'lat' => rand(10, 30) . '.' . rand(10000000000, 99999999999),
                'lng' => rand(70, 90) . '.' . rand(10000000000, 99999999999),
                'developer_project_id' => null,
                'property_type_id' => 1,
                'status' => 0,
                'created_by' => 7,
            ];
        }

        foreach ($societies as $society) {
            SocietyMasterModel::create($society);
        }
    }
}