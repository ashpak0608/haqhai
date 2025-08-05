<?php

namespace Database\Seeders;

use App\Models\PropertyListingMasterModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyListingSeeder extends Seeder
{
    public function run()
    {
        $listings = [];

        // Fetch foreign key ids
        $societyIds = DB::table('societies_master')->pluck('id')->toArray();
        $roleIds = DB::table('users_roles')->pluck('id')->toArray();
        $listingForIds = DB::table('property_listing_for')->pluck('id')->toArray();
        $ageIds = DB::table('property_age')->pluck('id')->toArray();
        $bedroomIds = DB::table('bedrooms_master')->pluck('id')->toArray();
        $floorIds = DB::table('floor_master')->pluck('id')->toArray();
        $furnishingIds = DB::table('furnishing_master')->pluck('id')->toArray();
        $areaUnitIds = DB::table('property_area_unit_master')->pluck('id')->toArray();
        $areaTypeIds = DB::table('property_area_types')->pluck('id')->toArray();
        $waterSupplyIds = DB::table('water_supply_master')->pluck('id')->toArray();
        $facingIds = DB::table('property_facing')->pluck('id')->toArray();
        $parkingIds = DB::table('parking_type_master')->pluck('id')->toArray();
        $shownByIds = DB::table('property_shown_by_master')->pluck('id')->toArray();
        $conditionIds = DB::table('property_condition_master')->pluck('id')->toArray();
        $availabilityIds = DB::table('availability_master')->pluck('id')->toArray();

        // Safety check
        if (
            empty($societyIds) || empty($roleIds) || empty($listingForIds) || empty($ageIds) ||
            empty($bedroomIds) || empty($floorIds) || empty($furnishingIds) || empty($areaUnitIds) ||
            empty($areaTypeIds) || empty($waterSupplyIds) || empty($facingIds) || empty($parkingIds) ||
            empty($shownByIds) || empty($conditionIds) || empty($availabilityIds)
        ) {
            throw new \Exception("One or more required FK datasets are missing.");
        }

        for ($i = 1; $i <= 10000; $i++) {
            $listings[] = [
                'property_id' => $societyIds[array_rand($societyIds)],
                'property_description' => 'Beautiful property with great amenities',
                'role_id' => $roleIds[array_rand($roleIds)],
                'property_listing_for_id' => $listingForIds[array_rand($listingForIds)],
                'property_age_id' => $ageIds[array_rand($ageIds)],
                'bedrooms_master_id' => $bedroomIds[array_rand($bedroomIds)],
                'balconies' => rand(1, 3),
                'price_per_sq_feet' => rand(5000, 20000),
                'total_tower' => rand(1, 5),
                'total_no_flat' => rand(50, 200),
                'floor_master_id' => $floorIds[array_rand($floorIds)],
                'total_floors' => rand(5, 30),
                'furnishing_master_id' => $furnishingIds[array_rand($furnishingIds)],
                'bathrooms' => rand(1, 4),
                'built_up_area' => rand(500, 3000),
                'carpet_area' => rand(400, 2500),
                'super_built_up_area' => rand(600, 3500),
                'property_area_unit_master_id' => $areaUnitIds[array_rand($areaUnitIds)],
                'property_area_type_id' => $areaTypeIds[array_rand($areaTypeIds)],
                'water_availability' => rand(1, 3),
                'water_supply' => $waterSupplyIds[array_rand($waterSupplyIds)],
                'property_facing_id' => $facingIds[array_rand($facingIds)],
                'parking_id' => $parkingIds[array_rand($parkingIds)],
                'is_sewage_connection' => rand(0, 1),
                'pet_allowed' => rand(0, 1),
                'gym' => rand(0, 1),
                'non_veg_allowed' => rand(0, 1),
                'gated_security' => rand(0, 1),
                'power_backup' => rand(0, 1),
                'property_shown_by_master_id' => $shownByIds[array_rand($shownByIds)],
                'property_condition_master_id' => $conditionIds[array_rand($conditionIds)],
                'secondary_number' => rand(9000000000, 9999999999),
                'availability_id' => $availabilityIds[array_rand($availabilityIds)],
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'is_available_all_day' => rand(0, 1),
                'status' => 0,
                'created_by' => 7,
            ];
        }

        foreach (array_chunk($listings, 50) as $chunk) {
            PropertyListingMasterModel::insert($chunk);
        }
    }
}
