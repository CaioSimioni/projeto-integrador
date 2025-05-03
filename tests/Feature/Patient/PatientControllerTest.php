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
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('patients.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => $page->component('patients/index'));
    }

    public function test_patient_can_be_created()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $formData = [
            'full_name' => 'João da Silva',
            'cpf' => '12345678900',
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'mother_name' => 'Maria da Silva',
            'father_name' => 'José da Silva',
            'sus_number' => '123456789012345',
            'medical_record' => 'MR-1234-AB',
            'nationality' => 'Brasileiro',
            'birth_place' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '23465-678',
            'address' => 'Rua das Flores, 123',
            'number' => '123',
            'complement' => 'Apto 101',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state_address' => 'SP',
            'country' => 'Brasil',
            'phone' => '(11) 98765-4321',
        ];

        $response = $this->post(route('patients.store'), $formData);

        $response->assertRedirect(route('patients.create'))->assertSessionHas('success');

        // Verifique os dados no banco
        $this->assertDatabaseHas('patients', [
            'full_name' => 'João da Silva',
            'cpf' => '12345678900',
        ]);
    }

    public function test_patient_creation_requires_mandatory_fields()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('patients.store'), [
            // Campos obrigatórios faltando
            'cpf' => '123.456.789-00',
        ]);

        $response->assertSessionHasErrors(['full_name', 'birth_date', 'gender', 'mother_name']);

    }

    public function test_patient_can_be_updated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $patient = Patient::factory()->create([
            'full_name' => 'Nome Original',
            'cpf' => '11122233344',
        ]);

        $updateData = [
            'full_name' => 'Nome Atualizado',
            'cpf' => '98765432109',
        ];

        $response = $this->patch(route('patients.update', $patient), $updateData);

        $response->assertRedirect(route('patients.edit', ['patient' => $patient->id]))
                 ->assertSessionHas('success', 'Paciente atualizado com sucesso!');

        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'full_name' => 'Nome Atualizado',
            'cpf' => '98765432109',
        ]);
    }

    public function test_patient_can_be_deleted()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $patient = Patient::factory()->create();

        $response = $this->delete(route('patients.destroy', $patient));

        $response->assertRedirect(route('patients.index'))->assertSessionHas('success');

        $this->assertDatabaseMissing('patients', ['id' => $patient->id]);
    }

    public function test_patient_list_route()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Patient::factory()->count(3)->create();

        $response = $this->get(route('patients.list'));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => $page->component('patients/patients-list'));
        $response->assertInertia(fn($page) => $page->has('patients', 3));
    }
}
