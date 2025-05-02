<?php

namespace Tests\Feature\Patient;

use Tests\TestCase;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_validation_requires_name()
    {
        // Simula um usuário autenticado
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('patients.store'), [
            'cpf' => '12345678901', // Nome faltando
            'birth_date' => '2000-01-01',
            'gender' => 'male',
            'mother_name' => 'Mary Doe',
        ]);

        // Verifica se o erro de validação para o campo "fullName" foi retornado
        $response->assertSessionHasErrors(['full_name']);
    }
}
