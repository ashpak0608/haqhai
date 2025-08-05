<?php

namespace Database\Seeders;

use App\Models\StateMaster;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    public function run()
    {
        $states = [
            ['state_name' => 'Maharashtra', 'country_id' => 1, 'status' => 0, 'created_by' => 1],
            ['state_name' => 'Delhi', 'country_id' => 1, 'status' => 0, 'created_by' => 1],
            ['state_name' => 'Karnataka', 'country_id' => 1, 'status' => 0, 'created_by' => 1],
            ['state_name' => 'Tamil Nadu', 'country_id' => 1, 'status' => 0, 'created_by' => 1],
            ['state_name' => 'West Bengal', 'country_id' => 1, 'status' => 0, 'created_by' => 1],
            ['state_name' => 'Telangana', 'country_id' => 1, 'status' => 0, 'created_by' => 1],
        ];

        foreach ($states as $state) {
            StateMaster::create($state);
        }
    }
}