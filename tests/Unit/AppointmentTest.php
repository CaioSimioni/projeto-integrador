<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_can_be_created()
    {
        $patient = Patient::factory()->create();

        $appointment = Appointment::factory()->create([
            'patient_id' => $patient->id,
            'appointment_date' => now()->addDay(),
            'notes' => 'Test appointment',
        ]);

        $this->assertDatabaseHas('appointments', [
            'patient_id' => $patient->id,
            'notes' => 'Test appointment',
        ]);
    }

    public function test_appointment_requires_patient_id_and_date()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Appointment::create([]); // Tenta criar uma consulta sem dados
    }

    public function test_appointment_belongs_to_patient()
    {
        $patient = Patient::factory()->create();
        $appointment = Appointment::factory()->create(['patient_id' => $patient->id]);

        $this->assertInstanceOf(Patient::class, $appointment->patient);
    }
}
