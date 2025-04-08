<?php

namespace Tests\Feature\Appointment;

use Tests\TestCase;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_appointments_index_route()
    {
        $user = User::factory()->create(); // Autentica o usuário
        $this->actingAs($user);

        $response = $this->get(route('appointments.index'));

        $response->assertStatus(200); // Verifica se a rota retorna status 200
        $response->assertInertia(fn ($page) => $page->component('appointments/index')); // Verifica se a view correta foi carregada
    }

    public function test_appointment_can_be_created()
    {
        $user = User::factory()->create(); // Autentica o usuário
        $this->actingAs($user);

        $patient = Patient::factory()->create();

        $data = [
            'patient_id' => $patient->id,
            'appointment_date' => now()->addDay()->format('Y-m-d H:i:s'),
            'notes' => 'Test appointment',
        ];

        $response = $this->post(route('appointments.store'), $data);

        $response->assertStatus(302); // Verifica se a criação redireciona
        $this->assertDatabaseHas('appointments', $data); // Verifica se os dados foram persistidos
    }

    public function test_appointment_can_be_updated()
    {
        $user = User::factory()->create(); // Autentica o usuário
        $this->actingAs($user);

        $appointment = Appointment::factory()->create();

        $data = [
            'patient_id' => $appointment->patient_id,
            'appointment_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'notes' => 'Updated appointment',
        ];

        $response = $this->patch(route('appointments.update', $appointment), $data);

        $response->assertStatus(302); // Verifica se a atualização redireciona
        $this->assertDatabaseHas('appointments', $data); // Verifica se os dados foram atualizados
    }

    public function test_appointment_can_be_deleted()
    {
        $user = User::factory()->create(); // Autentica o usuário
        $this->actingAs($user);

        $appointment = Appointment::factory()->create();

        $response = $this->delete(route('appointments.destroy', $appointment));

        $response->assertStatus(302); // Verifica se a exclusão redireciona
        $this->assertDatabaseMissing('appointments', ['id' => $appointment->id]); // Verifica se a consulta foi excluída
    }
}
