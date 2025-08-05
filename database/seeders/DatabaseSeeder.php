<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
            DistrictSeeder::class,
            PinLocationSeeder::class,
            PropertyTypeSeeder::class,
            PropertyCategorySeeder::class,
            BedroomsSeeder::class,
            FurnishingSeeder::class,
            PropertyAmenitySeeder::class,
            AroundAmenitySeeder::class,
            DeveloperSeeder::class,
            SocietiesSeeder::class,
            PropertyListingSeeder::class,
            RentalPropertySeeder::class,
            SellPropertySeeder::class,
        ]);
    }
}