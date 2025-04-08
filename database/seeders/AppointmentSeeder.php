<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = Patient::all();

        // Garantir que existam pacientes antes de criar os appointments
        if ($patients->isEmpty()) {
            $this->command->warn('No patients found. Skipping appointment seeding.');
            return;
        }

        // Criar 20 registros de appointments
        for ($i = 0; $i < 20; $i++) {
            Appointment::factory()->create([
                'patient_id' => $patients->random()->id, // Associar a um paciente aleatório
                'appointment_date' => now()->addDays(rand(1, 30)), // Data aleatória nos próximos 30 dias
                'notes' => 'Appointment notes ' . ($i + 1),
            ]);
        }
    }
}
