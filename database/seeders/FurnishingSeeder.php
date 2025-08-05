<?php

namespace Database\Seeders;

use App\Models\FurnishingMaster;
use Illuminate\Database\Seeder;

class FurnishingSeeder extends Seeder
{
    public function run()
    {
        $furnishings = [
            ['furnishing_type' => 'Fully Furnished', 'status' => 0, 'created_by' => 1],
            ['furnishing_type' => 'Semi Furnished', 'status' => 0, 'created_by' => 1],
            ['furnishing_type' => 'Unfurnished', 'status' => 0, 'created_by' => 1],
        ];

        foreach ($furnishings as $furnishing) {
            FurnishingMaster::create($furnishing);
        }
    }
}