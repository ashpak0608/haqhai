<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SellPropertyListingModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SellPropertyListingSeeder extends Seeder
{
    public function run()
    {
        $properties = [];

        // Assuming IDs exist in DB. Replace with actual pluck if needed.
        $propertyIds = DB::table('societies_master')->pluck('id')->toArray();
        $listingIds = DB::table('property_listing_master')->pluck('id')->toArray();
        $kitchenTypeIds = DB::table('kitchen_type_master')->pluck('id')->toArray();
        $ownershipTypeIds = DB::table('property_ownership_type')->pluck('id')->toArray();

        if (empty($propertyIds) || empty($listingIds) || empty($ownershipTypeIds)) {
            throw new \Exception("Required foreign key data missing.");
        }

        for ($i = 1; $i <= 10000; $i++) {
            $properties[] = [
                'property_id' => $propertyIds[array_rand($propertyIds)],
                'property_listing_master_id' => $listingIds[array_rand($listingIds)],
                'property_ownership_type_id' => $ownershipTypeIds[array_rand($ownershipTypeIds)],
                'expected_price' => rand(2500000, 50000000), // numeric
                'expected_price_negotiable' => rand(0, 1), // in:0,1
                'maintenance_cost' => rand(0, 1) ? rand(500, 5000) : null, // nullable numeric
                'is_currently_under_loan' => rand(0, 1), // in:0,1
                'available_from' => Carbon::now()->addDays(rand(1, 90))->format('Y-m-d'), // date
                'kitchen_type_id' => !empty($kitchenTypeIds) ? $kitchenTypeIds[array_rand($kitchenTypeIds)] : null, // nullable
                'do_you_have_allotment_letter' => rand(0, 2), // in:0,1,2
                'do_you_have_sale_deed_certificate' => rand(0, 2), // in:0,1,2
                'is_paid_property_tax' => rand(0, 2), // in:0,1,2
                'is_occupancy_certificate_available' => rand(0, 2), // in:0,1,2
                'status' => 0,
                'created_by' => 7,
                'created_at' => now(),
                'updated_by' => null,
                'updated_at' => null,
            ];
        }

        foreach (array_chunk($properties, 500) as $chunk) {
            SellPropertyListingModel::insert($chunk);
        }
    }
}
