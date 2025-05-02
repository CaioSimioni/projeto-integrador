<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\PatientSeeder;
use Database\Seeders\AppointmentSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class); // Chamar o UserSeeder
        $this->call(PatientSeeder::class); // Chamar o PatientSeeder
        /* $this->call(AppointmentSeeder::class); // Chamar o AppointmentSeeder */
    }
}
