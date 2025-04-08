<?php

namespace Tests\Feature\Patient;

use Tests\TestCase;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_is_required()
    {
        // Simula um usuário autenticado
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Tenta criar um paciente sem o campo "name"
        $response = $this->post(route('patients.store'), [
            'cpf' => '12345678901', // Nome faltando
        ]);

        // Verifica se o erro de validação para o campo "name" foi retornado
        $response->assertSessionHasErrors(['name']);
    }
}
