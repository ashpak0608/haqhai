<?php

namespace Database\Seeders;

use App\Models\PropertyTypeMaster;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['property_type' => 'Residential', 'type_name' => 'Apartment', 'status' => 0, 'created_by' => 1],
            ['property_type' => 'Residential', 'type_name' => 'Villa', 'status' => 0, 'created_by' => 1],
            ['property_type' => 'Residential', 'type_name' => 'Independent House', 'status' => 0, 'created_by' => 1],
            ['property_type' => 'Commercial', 'type_name' => 'Office Space', 'status' => 0, 'created_by' => 1],
            ['property_type' => 'Commercial', 'type_name' => 'Shop', 'status' => 0, 'created_by' => 1],
            ['property_type' => 'Commercial', 'type_name' => 'Warehouse', 'status' => 0, 'created_by' => 1],
        ];

        foreach ($types as $type) {
            PropertyTypeMaster::create($type);
        }
    }
}