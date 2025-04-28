<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company_1 = Company::firstOrCreate([
            'name' => 'Champs',
            'phone_number' => '81234432',
            'email' => 'champs@gmail.com',
            'website' => 'www.champs.com'
        ]);

        $company_2 = Company::firstOrCreate([
            'name' => 'Oppa',
            'phone_number' => '81987876',
            'email' => 'oppa@gmail.com',
            'website' => 'www.oppa.com'
        ]);

        User::firstOrCreate([
            'company_id' => $company_1->id,
            'name' => 'fadi',
            'email' => 'fadi@champs.com',
            'password' => bcrypt('fadi123'),
            'email_verified_at' => now()
        ]);

        User::firstOrCreate([
            'company_id' => $company_1->id,
            'name' => 'lea',
            'email' => 'lea@champs.com',
            'password' => bcrypt('lea123'),
            'email_verified_at' => now()
        ]);

        User::firstOrCreate([
            'company_id' => $company_2->id,
            'name' => 'maroun',
            'email' => 'maroun@oppa.com',
            'password' => bcrypt('maroun123'),
            'email_verified_at' => now()
        ]);

        User::firstOrCreate([
            'company_id' => $company_2->id,
            'name' => 'sarah',
            'email' => 'sarah@oppa.com',
            'password' => bcrypt('sarah123'),
            'email_verified_at' => now()
        ]);
    }
}
