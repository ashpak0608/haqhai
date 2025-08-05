<?php

namespace Database\Seeders;

use App\Models\RentalPropertyListingModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RentalPropertySeeder extends Seeder
{
    public function run()
    {
        // Optional: Clear old rental records (uncomment if needed)
        // DB::table('rental_property_listing_master')->truncate();

        $propertyIds = DB::table('societies_master')->pluck('id')->toArray();
        $listingIds = DB::table('property_listing_master')->pluck('id')->toArray();

        if (empty($propertyIds) || empty($listingIds)) {
            throw new \Exception("Required foreign key data is missing. Ensure societies and listings are seeded first.");
        }

        $rentals = [];

        for ($i = 1; $i <= 10000; $i++) {
            $city = $this->getRandomCity();

            $rentals[] = [
                'property_id' => $propertyIds[array_rand($propertyIds)],
                'property_listing_master_id' => $listingIds[array_rand($listingIds)],
                'rental_type_master_id' => rand(1, 2),
                'property_rent' => $this->getRentBasedOnCity($city),
                'property_deposit' => $this->getDepositBasedOnCity($city),
                'property_rent_negotiable' => rand(0, 1),
                'maintenance_type_master_id' => rand(1, 2),
                'monthly_maintenance_amount' => $this->getMaintenanceBasedOnCity($city),
                'available_from' => now()->addDays(rand(1, 90))->format('Y-m-d'),
                'preferred_tenant_id' => rand(1, 5),
                'add_directions_tip_for_your_tenants' => $this->getDirectionsTip($city),
                'status' => 0,
                'created_by' => 7,
                'created_at' => now(),
                'updated_by' => null,
                'updated_at' => null,
            ];
        }

        // Insert in chunks
        foreach (array_chunk($rentals, 500) as $chunk) {
            RentalPropertyListingModel::insert($chunk);
        }
    }

    private function getRandomCity()
    {
        $cities = ['Mumbai', 'Delhi', 'Bangalore', 'Hyderabad', 'Chennai', 'Kolkata', 'Pune'];
        return $cities[array_rand($cities)];
    }

    private function getRentBasedOnCity($city)
    {
        return match ($city) {
            'Mumbai' => rand(15000, 150000),
            'Bangalore' => rand(12000, 100000),
            'Delhi' => rand(10000, 90000),
            'Hyderabad' => rand(8000, 70000),
            'Chennai' => rand(7000, 60000),
            'Pune' => rand(9000, 80000),
            'Kolkata' => rand(6000, 50000),
            default => rand(5000, 40000),
        };
    }

    private function getDepositBasedOnCity($city)
    {
        return match ($city) {
            'Mumbai' => rand(100000, 500000),
            'Bangalore' => rand(80000, 400000),
            'Delhi' => rand(60000, 300000),
            default => rand(40000, 200000),
        };
    }

    private function getMaintenanceBasedOnCity($city)
    {
        return match ($city) {
            'Mumbai' => rand(1000, 5000),
            'Bangalore' => rand(800, 4000),
            default => rand(500, 3000),
        };
    }

    private function getDirectionsTip($city)
    {
        $tips = [
            'Mumbai' => 'Near to metro station, 5 mins walk',
            'Bangalore' => 'Close to tech parks, good connectivity',
            'Delhi' => 'Near to metro, market nearby',
            'Hyderabad' => 'Close to IT corridor, good roads',
            'Chennai' => 'Beach nearby, peaceful locality',
            'Pune' => 'Educational institutions nearby',
            'Kolkata' => 'Cultural hub, good transport',
        ];

        return $tips[$city] ?? 'Well connected area with amenities';
    }
}
