<?php

namespace Database\Seeders;

use App\Models\CountryMaster;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $countries = [
            ['country_name' => 'India', 'status' => 0, 'created_by' => 1],
            ['country_name' => 'United States', 'status' => 0, 'created_by' => 1],
            ['country_name' => 'United Kingdom', 'status' => 0, 'created_by' => 1],
        ];

        foreach ($countries as $country) {
            CountryMaster::create($country);
        }
    }
}