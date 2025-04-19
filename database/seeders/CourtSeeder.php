<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Complex;
use App\Models\Court;
use App\Models\CourtType;
use App\Models\SurfaceType;
use App\Models\Reservation;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CourtSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Allowed durations
        $allowedDurations = [0.5, 0.75, 1.0, 1.25, 1.5, 1.75, 2.0];

        // Create 5 complexes
        $complexes = collect();
        for ($i = 1; $i <= 5; $i++) {
            $complexes->push(Complex::create([
                'company_id' => 1,
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

        // Create 30 courts with allowed reservation_duration
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
                'reservation_duration' => $faker->randomElement($allowedDurations),
            ]);
        }

        $courtsWithDurations = Court::whereNotNull('reservation_duration')->get();

        foreach ($courtsWithDurations->random(10) as $court) {
            $date = Carbon::now()->addDays(rand(0, 7))->toDateString();
            $duration = $court->reservation_duration;

            $startTime = Carbon::createFromTime(rand(8, 20), 0, 0);
            $endTime = (clone $startTime)->addMinutes($duration * 60);

            Reservation::create([
                'court_id' => $court->id,
                'section_id' => null,
                'customer_id' => 1,
                'reservation_date' => $date,
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'total_price' => $court->hourly_rate * $duration,
                'is_canceled' => false,
                'is_no_show' => false,
            ]);
        }

        $this->command->info('5 complexes, 30 courts with allowed durations, and 10 reservations created.');
    }
}
