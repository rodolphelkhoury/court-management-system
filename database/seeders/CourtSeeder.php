<?php

namespace Database\Seeders;

use App\Domain\CourtType\Enums\CourtTypeName;
use App\Domain\SurfaceType\Enums\SurfaceTypeName;
use Illuminate\Database\Seeder;
use App\Models\Complex;
use App\Models\Court;
use App\Models\CourtType;
use App\Models\Image;
use App\Models\SurfaceType;
use App\Models\Reservation;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CourtSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $allowedDurations = [0.5, 0.75, 1.0, 1.25, 1.5, 1.75, 2.0];

        $complexes = collect();
        for ($i = 1; $i <= 5; $i++) {
            $complexes->push(Complex::create([
                'company_id' => 1,
                'name' => "Complex $i",
                'description' => "asdfasfda",
                'line1' => $faker->streetAddress,
                'line2' => $faker->secondaryAddress,
                'city' => $faker->city,
                'country' => $faker->country,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
            ]));
        }

        $rebound_court = Court::create([
            'complex_id' => $complexes->random()->id,
            'court_type_id' => CourtType::where('name', CourtTypeName::BASKETBALL->value)->first()->id,
            'surface_type_id' => SurfaceType::where('name', SurfaceTypeName::HARD_ACRYLIC->value)->first()->id,
            'name' => 'Rebound Club',
            'description' => 'Indoor basketball with acrylic surface and modern vibes.',
            'hourly_rate' => 10,
            'divisible' => (bool) rand(0, 1),
            'max_divisions' => rand(1, 4),
            'opening_time' => '08:00:00',
            'closing_time' => '22:00:00',
            'reservation_duration' => $faker->randomElement($allowedDurations),
            'longitude' => 35.569310079703214,
            'latitude' => 33.89134451178183, 
            'address_line' => 'beirut, jdeideh'
        ]);

        Image::create([
            'disk' => 'public',
            'name' => 'primary',
            'filepath' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTqPFUxIlKiNL7oRLwnZrO3iJ0duozmL5ZqhQ&s',
            'mimetype' => 'png',
            'width' => 500,
            'height' => 200,
            'filesize' => 500,
            'owner_type' => $rebound_court::class,
            'owner_id' => $rebound_court->id
        ]);

        $fc_stadium_court = Court::create([
            'complex_id' => $complexes->random()->id,
            'court_type_id' => CourtType::where('name', CourtTypeName::SOCCER->value)->first()->id,
            'surface_type_id' => SurfaceType::where('name', SurfaceTypeName::GRASS_SYNTHETIC->value)->first()->id,
            'name' => 'FCSTADIUM',
            'description' => 'Top-tier soccer on synthetic grass fields.',
            'hourly_rate' => 5,
            'divisible' => (bool) rand(0, 1),
            'max_divisions' => rand(1, 4),
            'opening_time' => '08:00:00',
            'closing_time' => '23:00:00',
            'reservation_duration' => $faker->randomElement($allowedDurations), 
            'longitude' => 35.55193522379884,
            'latitude' => 33.88392238125481,
            'address_line' => 'beirut, dekwaneh'
        ]);

        Image::create([
            'disk' => 'public',
            'name' => 'primary',
            'filepath' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTEcBOdsJO7rmmd2FZ-7ifAASQ7l0VDENTf9VP1zOk2Wfzm4FYmhNm5TC5WdTbSdcDUFrc&usqp=CAU',
            'mimetype' => 'png',
            'width' => 500,
            'height' => 200,
            'filesize' => 500,
            'owner_type' => $fc_stadium_court::class,
            'owner_id' => $fc_stadium_court->id
        ]);

        $oppa_club_court = Court::create([
            'complex_id' => $complexes->random()->id,
            'court_type_id' => CourtType::where('name', CourtTypeName::SOCCER->value)->first()->id,
            'surface_type_id' => SurfaceType::where('name', SurfaceTypeName::GRASS_NATURAL->value)->first()->id,
            'name' => 'OPPA CLUB',
            'description' => 'Classic soccer court with natural grass experience.',
            'hourly_rate' => 25,
            'divisible' => (bool) rand(0, 1),
            'max_divisions' => rand(1, 4),
            'opening_time' => '08:00:00',
            'closing_time' => '23:00:00',
            'reservation_duration' => $faker->randomElement($allowedDurations),
            'longitude' => 35.560716282447956,
            'latitude' => 33.892653679241505,
            'address_line' => 'beirut, baouchriyeh'
        ]);

        Image::create([
            'disk' => 'public',
            'name' => 'primary',
            'filepath' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT2IjVvClvIHzHZolq7dPn4EtX_Y_FxW40MSA&s',
            'mimetype' => 'png',
            'width' => 500,
            'height' => 200,
            'filesize' => 500,
            'owner_type' => $oppa_club_court::class,
            'owner_id' => $oppa_club_court->id
        ]);

        $paddle_house_court = Court::create([
            'complex_id' => $complexes->random()->id,
            'court_type_id' => CourtType::where('name', CourtTypeName::PADEL->value)->first()->id,
            'surface_type_id' => SurfaceType::where('name', SurfaceTypeName::SYNTHETIC_RUBBER->value)->first()->id,
            'name' => 'Padel House',
            'description' => 'Classic padel court.',
            'hourly_rate' => 15,
            'divisible' => (bool) rand(0, 1),
            'max_divisions' => rand(1, 4),
            'opening_time' => '08:00:00',
            'closing_time' => '23:00:00',
            'reservation_duration' => $faker->randomElement($allowedDurations),
            'longitude' => 35.56679876612841,
            'latitude' => 33.89982744957849,
            'address_line' => 'beirut, dekwaneh'
        ]);

        Image::create([
            'disk' => 'public',
            'name' => 'primary',
            'filepath' => 'https://lh3.googleusercontent.com/p/AF1QipMgEILqeHGL2pnAHr2uJ1B6_ItkPdREm9r8OWte=w426-h240-k-no',
            'mimetype' => 'png',
            'width' => 500,
            'height' => 200,
            'filesize' => 500,
            'owner_type' => $paddle_house_court::class,
            'owner_id' => $paddle_house_court->id
        ]);

        $the_v_club = Court::create([
            'complex_id' => $complexes->random()->id,
            'court_type_id' => CourtType::where('name', CourtTypeName::TENNIS->value)->first()->id,
            'surface_type_id' => SurfaceType::where('name', SurfaceTypeName::HARD_ACRYLIC->value)->first()->id,
            'name' => 'The V Club',
            'description' => 'Classic tennis court on hard acrylic ground.',
            'hourly_rate' => 30,
            'divisible' => (bool) rand(0, 1),
            'max_divisions' => rand(1, 4),
            'opening_time' => '08:00:00',
            'closing_time' => '23:00:00',
            'reservation_duration' => $faker->randomElement($allowedDurations),
            'longitude' => 35.583691381556115,
            'latitude' => 33.91073267926788,
            'address_line' => 'antelias'
        ]);

        Image::create([
            'disk' => 'public',
            'name' => 'primary',
            'filepath' => 'https://lh3.googleusercontent.com/gps-cs-s/AB5caB_nCXZ35NU7nMuq7bJBUCJPZD1KGxDolpI16BxlfnOjqVCIO6hvhGbecl2Kl85t_Qq65G4gRSoWRofGhog1T_HQYaODxlBY7tgJNgW6mD6oqr4uXeiufgC0yZezJFBDY3eIpUk=w408-h306-k-no',
            'mimetype' => 'png',
            'width' => 500,
            'height' => 200,
            'filesize' => 500,
            'owner_type' => $the_v_club::class,
            'owner_id' => $the_v_club->id
        ]);

        $elite_court = Court::create([
            'complex_id' => $complexes->random()->id,
            'court_type_id' => CourtType::where('name', CourtTypeName::SOCCER->value)->first()->id,
            'surface_type_id' => SurfaceType::where('name', SurfaceTypeName::SYNTHETIC_RUBBER->value)->first()->id,
            'name' => '5-18 Elite Football Academy',
            'description' => 'Classic soccer court on synthetic rubber ground.',
            'hourly_rate' => 8,
            'divisible' => (bool) rand(0, 1),
            'max_divisions' => rand(1, 4),
            'opening_time' => '08:00:00',
            'closing_time' => '20:00:00',
            'reservation_duration' => $faker->randomElement($allowedDurations),
            'longitude' => 35.549386164258955,
            'latitude' => 33.87712173648952,
            'address_line' => 'beirut, dekwaneh'
        ]);

        Image::create([
            'disk' => 'public',
            'name' => 'primary',
            'filepath' => 'https://lh3.googleusercontent.com/p/AF1QipPV36Abd9-X9LJigE_rozBthMLtuubHyjMAATnB=w426-h240-k-no',
            'mimetype' => 'png',
            'width' => 500,
            'height' => 200,
            'filesize' => 500,
            'owner_type' => $elite_court::class,
            'owner_id' => $elite_court->id
        ]);

        $clutch_court = Court::create([
            'complex_id' => $complexes->random()->id,
            'court_type_id' => CourtType::where('name', CourtTypeName::BASKETBALL->value)->first()->id,
            'surface_type_id' => SurfaceType::where('name', SurfaceTypeName::HARD_ASPHALT->value)->first()->id,
            'name' => 'Clutch',
            'description' => 'Classic basketball court on synthetic rubber ground.',
            'hourly_rate' => 22,
            'divisible' => (bool) rand(0, 1),
            'max_divisions' => rand(1, 4),
            'opening_time' => '08:00:00',
            'closing_time' => '21:00:00',
            'reservation_duration' => $faker->randomElement($allowedDurations),
            'longitude' => 35.58017434347861,
            'latitude' => 33.876172843516265, 
            'address_line' => 'matn, fanar'
        ]);

        Image::create([
            'disk' => 'public',
            'name' => 'primary',
            'filepath' => 'https://lh3.googleusercontent.com/p/AF1QipPfm-_yU0KG_RZUKjXoXciyfn6hJUTeTGjD0SIW=w408-h306-k-no',
            'mimetype' => 'png',
            'width' => 500,
            'height' => 200,
            'filesize' => 500,
            'owner_type' => $clutch_court::class,
            'owner_id' => $clutch_court->id
        ]);

        $champs_court = Court::create([
            'complex_id' => $complexes->random()->id,
            'court_type_id' => CourtType::where('name', CourtTypeName::BASKETBALL->value)->first()->id,
            'surface_type_id' => SurfaceType::where('name', SurfaceTypeName::HARD_ASPHALT->value)->first()->id,
            'name' => 'Champs Fitness Development Center',
            'description' => 'Your premium fitness studio in Mission Bay',
            'hourly_rate' => 30,
            'divisible' => (bool) rand(0, 1),
            'max_divisions' => rand(1, 4),
            'opening_time' => '08:00:00',
            'closing_time' => '23:00:00',
            'reservation_duration' => $faker->randomElement($allowedDurations),
            'longitude' => 35.52879636216507,
            'latitude' => 33.86546653087971,
            'address_line' => 'baabda, hazmieh'
        ]);

        Image::create([
            'disk' => 'public',
            'name' => 'primary',
            'filepath' => 'https://lh3.googleusercontent.com/gps-cs-s/AB5caB_GUb7ClrqyE0sgd8Wy6EDpuPjfKCzAEhhi27fcpRfs0POFbPMhIzd029LEBOU9q7sJufiZJkwCpl1_grivcUlRU0d3mbCub7b-knpnYRHDrN6tCGjSJVLTsT-t46k5bf1KfMhE=w408-h306-k-no',
            'mimetype' => 'png',
            'width' => 500,
            'height' => 200,
            'filesize' => 500,
            'owner_type' => $champs_court::class,
            'owner_id' => $champs_court->id
        ]);
    }
}
