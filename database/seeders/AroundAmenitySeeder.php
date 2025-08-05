<?php

namespace Database\Seeders;

use App\Models\AroundPropertyAmenityMaster;
use Illuminate\Database\Seeder;

class AroundAmenitySeeder extends Seeder
{
    public function run()
    {
        $amenities = [
            ['around_amenity_name' => 'School', 'status' => 0, 'created_by' => 1],
            ['around_amenity_name' => 'Hospital', 'status' => 0, 'created_by' => 1],
            ['around_amenity_name' => 'Shopping Mall', 'status' => 0, 'created_by' => 1],
            ['around_amenity_name' => 'Metro Station', 'status' => 0, 'created_by' => 1],
            ['around_amenity_name' => 'Bus Stop', 'status' => 0, 'created_by' => 1],
        ];

        foreach ($amenities as $amenity) {
            AroundPropertyAmenityMaster::create($amenity);
        }
    }
}