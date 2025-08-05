<?php

namespace Database\Seeders;

use App\Models\PinLocationMaster;
use Illuminate\Database\Seeder;

class PinLocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            ['location_name' => 'Andheri East', 'pincode' => '400069', 'district_id' => 1, 'status' => 0, 'created_by' => 1],
            ['location_name' => 'Bandra West', 'pincode' => '400050', 'district_id' => 1, 'status' => 0, 'created_by' => 1],
            ['location_name' => 'Koramangala', 'pincode' => '560034', 'district_id' => 4, 'status' => 0, 'created_by' => 1],
            ['location_name' => 'Whitefield', 'pincode' => '560066', 'district_id' => 4, 'status' => 0, 'created_by' => 1],
            ['location_name' => 'Connaught Place', 'pincode' => '110001', 'district_id' => 3, 'status' => 0, 'created_by' => 1],
            ['location_name' => 'Hauz Khas', 'pincode' => '110016', 'district_id' => 3, 'status' => 0, 'created_by' => 1],
            ['location_name' => 'Gachibowli', 'pincode' => '500032', 'district_id' => 7, 'status' => 0, 'created_by' => 1],
            ['location_name' => 'Jubilee Hills', 'pincode' => '500033', 'district_id' => 7, 'status' => 0, 'created_by' => 1],
        ];

        foreach ($locations as $location) {
            PinLocationMaster::create($location);
        }
    }
}