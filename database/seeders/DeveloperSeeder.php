<?php

namespace Database\Seeders;

use App\Models\DeveloperMaster;
use Illuminate\Database\Seeder;

class DeveloperSeeder extends Seeder
{
    public function run()
    {
        $developers = [
            ['developer_name' => 'Lodha Group', 'status' => 0, 'created_by' => 1],
            ['developer_name' => 'DLF Limited', 'status' => 0, 'created_by' => 1],
            ['developer_name' => 'Prestige Group', 'status' => 0, 'created_by' => 1],
            ['developer_name' => 'Sobha Limited', 'status' => 0, 'created_by' => 1],
            ['developer_name' => 'Godrej Properties', 'status' => 0, 'created_by' => 1],
        ];

        foreach ($developers as $developer) {
            DeveloperMaster::create($developer);
        }
    }
}