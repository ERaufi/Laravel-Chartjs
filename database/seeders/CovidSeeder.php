<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CovidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Define the date range
        $startDate = '2019-01-01';
        $endDate = '2023-12-31';
        $currentDate = $startDate;

        // Loop through each day
        while ($currentDate <= $endDate) {
            // Generate 100 records for each day
            for ($i = 0; $i < 100; $i++) {
                DB::table('covids')->insert([
                    'country_id' => $faker->numberBetween(1, 100),
                    'date' => $currentDate,
                    'Confirmed' => $faker->numberBetween(0, 1000),
                    'Deaths' => $faker->numberBetween(0, 100),
                    'Recovered' => $faker->numberBetween(0, 500),
                    'Active' => $faker->numberBetween(0, 500),
                ]);
            }

            // Move to the next day
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }
    }
}
