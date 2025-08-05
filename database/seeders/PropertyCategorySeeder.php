<?php

namespace Database\Seeders;

use App\Models\PropertyCategoryMaster;
use Illuminate\Database\Seeder;

class PropertyCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['category_name' => 'Apartment', 'status' => 0, 'created_by' => 1],
            ['category_name' => 'Villa', 'status' => 0, 'created_by' => 1],
            ['category_name' => 'Plot', 'status' => 0, 'created_by' => 1],
            ['category_name' => 'Commercial', 'status' => 0, 'created_by' => 1],
        ];

        foreach ($categories as $category) {
            PropertyCategoryMaster::create($category);
        }
    }
}