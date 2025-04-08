<?php

namespace Tests\Feature\Appointment;

use Tests\TestCase;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointment_date_is_required()
    {
        $user = User::factory()->create(); // Autentica o usuário
        $this->actingAs($user);

        $patient = Patient::factory()->create();

        $response = $this->post(route('appointments.store'), [
            'patient_id' => $patient->id,
            'notes' => 'Test appointment', // Data faltando
        ]);

        $response->assertSessionHasErrors(['appointment_date']); // Verifica se o erro de validação foi retornado
    }

    public function test_patient_id_is_required()
    {
        $user = User::factory()->create(); // Autentica o usuário
        $this->actingAs($user);

        $response = $this->post(route('appointments.store'), [
            'appointment_date' => now()->addDay()->format('Y-m-d H:i:s'),
            'notes' => 'Test appointment', // Patient ID faltando
        ]);

        $response->assertSessionHasErrors(['patient_id']); // Verifica se o erro de validação foi retornado
    }
}
