<?php

namespace Database\Seeders;


use App\Models\Court;
use App\Models\Complex;
use App\Models\CourtType;
use App\Models\SurfaceType;
use Illuminate\Database\Seeder;

class CourtSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Create 5 complexes
        $complexes = collect();
        for ($i = 1; $i <= 5; $i++) {
            $complexes->push(Complex::create([
                'company_id' => 1, // You can randomize or adjust based on your DB
                'name' => "Complex $i",
                'description' => $faker->paragraph,
                'line1' => $faker->streetAddress,
                'line2' => $faker->secondaryAddress,
                'city' => $faker->city,
                'country' => $faker->country,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
            ]));
        }

        $courtTypes = CourtType::all();
        $surfaceTypes = SurfaceType::all();

        if ($courtTypes->isEmpty() || $surfaceTypes->isEmpty()) {
            $this->command->warn('Make sure you have data in court_types and surface_types tables.');
            return;
        }

        // Create 30 courts
        for ($i = 1; $i <= 30; $i++) {
            Court::create([
                'complex_id' => $complexes->random()->id,
                'court_type_id' => $courtTypes->random()->id,
                'surface_type_id' => $surfaceTypes->random()->id,
                'name' => "Court $i",
                'description' => $faker->sentence,
                'hourly_rate' => rand(20, 100),
                'divisible' => (bool)rand(0, 1),
                'max_divisions' => rand(1, 4),
                'opening_time' => '08:00:00',
                'closing_time' => '22:00:00',
            ]);
        }

        $this->command->info('5 complexes and 30 courts created successfully.');
    }
}

