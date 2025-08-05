<?php

namespace Database\Seeders;

use App\Models\BedroomsMaster;
use Illuminate\Database\Seeder;

class BedroomsSeeder extends Seeder
{
    public function run()
    {
        $bedrooms = [
            ['bedroom_nos' => '1', 'sequence' => 1, 'status' => 0, 'created_by' => 1],
            ['bedroom_nos' => '2', 'sequence' => 2, 'status' => 0, 'created_by' => 1],
            ['bedroom_nos' => '3', 'sequence' => 3, 'status' => 0, 'created_by' => 1],
            ['bedroom_nos' => '4', 'sequence' => 4, 'status' => 0, 'created_by' => 1],
            ['bedroom_nos' => '5+', 'sequence' => 5, 'status' => 0, 'created_by' => 1],
            ['bedroom_nos' => 'Studio', 'sequence' => 6, 'status' => 0, 'created_by' => 1],
        ];

        foreach ($bedrooms as $bedroom) {
            BedroomsMaster::create($bedroom);
        }
    }
}