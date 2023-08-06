<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $countryNames = [];

        for ($i = 0; $i < 100; $i++) {
            $countryName = $faker->unique()->country;
            array_push($countryNames, $countryName);
        }

        foreach ($countryNames as $countryName) {
            DB::table('countries')->insert([
                'name' => $countryName,
            ]);
        }
    }
}
