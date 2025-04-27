<?php

namespace Tests\Feature\Patient;

use Tests\TestCase;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_patients_index_route()
    {
        $user = User::factory()->create(); // Autentica o usuário
        $this->actingAs($user);

        $response = $this->get(route('patients.index'));

        $response->assertStatus(200); // Verifica se a rota retorna status 200
        $response->assertInertia(fn($page) => $page->component('patients/index'));
    }

    public function test_patient_can_be_created()
    {
        $user = User::factory()->create(); // Autentica o usuário
        $this->actingAs($user);

        $data = [
            'name' => 'John Doe',
            'cpf' => '12345678901',
            'birth_date' => '1990-01-01 00:00:00', // Formato completo
            'phone' => '1234567890',
            'email' => 'john@example.com',
            'address' => '123 Main St',
            'insurance' => 'Nenhum',
            'is_active' => 1, // Use 1 em vez de true
        ];

        $response = $this->post(route('patients.store'), $data);

        $response->assertStatus(302); // Verifica se a criação redireciona
        $this->assertDatabaseHas('patients', $data); // Verifica se os dados foram persistidos
    }

    public function test_patient_can_be_updated()
    {
        $user = User::factory()->create(); // Autentica o usuário
        $this->actingAs($user);

        $patient = Patient::factory()->create();

        $data = [
            'name' => 'Jane Doe',
            'cpf' => '98765432109',
        ];

        $response = $this->patch(route('patients.update', $patient), $data);

        $response->assertStatus(302); // Verifica se a atualização redireciona
        $this->assertDatabaseHas('patients', $data); // Verifica se os dados foram atualizados
    }

    public function test_patient_can_be_deleted()
    {
        $user = User::factory()->create(); // Autentica o usuário
        $this->actingAs($user);

        $patient = Patient::factory()->create();

        $response = $this->delete(route('patients.destroy', $patient));

        $response->assertStatus(302); // Verifica se a resposta redireciona
        $this->assertDatabaseMissing('patients', ['id' => $patient->id]); // Verifica se o paciente foi removido
    }
}
