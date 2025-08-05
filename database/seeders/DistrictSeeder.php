<?php

namespace Database\Seeders;

use App\Models\DistrictMaster;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run()
    {
        $districts = [
            ['district_name' => 'Mumbai', 'state_id' => 1, 'display_in_home_page' => 1, 'status' => 0, 'created_by' => 1],
            ['district_name' => 'Pune', 'state_id' => 1, 'display_in_home_page' => 1, 'status' => 0, 'created_by' => 1],
            ['district_name' => 'New Delhi', 'state_id' => 2, 'display_in_home_page' => 1, 'status' => 0, 'created_by' => 1],
            ['district_name' => 'Bangalore', 'state_id' => 3, 'display_in_home_page' => 1, 'status' => 0, 'created_by' => 1],
            ['district_name' => 'Chennai', 'state_id' => 4, 'display_in_home_page' => 1, 'status' => 0, 'created_by' => 1],
            ['district_name' => 'Kolkata', 'state_id' => 5, 'display_in_home_page' => 1, 'status' => 0, 'created_by' => 1],
            ['district_name' => 'Hyderabad', 'state_id' => 6, 'display_in_home_page' => 1, 'status' => 0, 'created_by' => 1],
        ];

        foreach ($districts as $district) {
            DistrictMaster::create($district);
        }
    }
}