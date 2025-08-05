<?php

namespace Database\Seeders;

use App\Models\PropertyAmenityMaster;
use Illuminate\Database\Seeder;

class PropertyAmenitySeeder extends Seeder
{
    public function run()
    {
        $amenities = [
            ['amenity_name' => 'Swimming Pool', 'property_amenity_type' => 'Building Amenities', 'status' => 0, 'created_by' => 1],
            ['amenity_name' => 'Gym', 'property_amenity_type' => 'Building Amenities', 'status' => 0, 'created_by' => 1],
            ['amenity_name' => 'Park', 'property_amenity_type' => 'Building Amenities', 'status' => 0, 'created_by' => 1],
            ['amenity_name' => 'Power Backup', 'property_amenity_type' => 'Building Amenities', 'status' => 0, 'created_by' => 1],
            ['amenity_name' => 'Lift', 'property_amenity_type' => 'Building Amenities', 'status' => 0, 'created_by' => 1],
            ['amenity_name' => 'Sofa', 'property_amenity_type' => 'Furnishings', 'status' => 0, 'created_by' => 1],
            ['amenity_name' => 'Dining Table', 'property_amenity_type' => 'Furnishings', 'status' => 0, 'created_by' => 1],
            ['amenity_name' => 'Wardrobe', 'property_amenity_type' => 'Furnishings', 'status' => 0, 'created_by' => 1],
        ];

        foreach ($amenities as $amenity) {
            PropertyAmenityMaster::create($amenity);
        }
    }
}