<?php

namespace Tests\Feature\Patient;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_store_patient()
    {
        $response = $this->post(route('patients.store'), [
            'full_name' => 'John Doe',
            'cpf' => '12345678901',
        ]);

        $response->assertFound(); // Eu sei que o certo seria 401, mas o Laravel só responde 302.  ¯\_(ツ)_/¯
    }

    public function test_authenticated_user_can_store_patient()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('patients.store'), [
            'full_name' => 'João da Silva',  // Nome correto
            'cpf' => '12345678900',  // CPF correto
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
        ]);

        $response->assertFound(); // Verifica se a criação redireciona
        // Verifique se o paciente foi realmente salvo com os dados corretos
        $this->assertDatabaseHas('patients', [
            'full_name' => 'João da Silva',  // Alinhar com o nome que foi enviado
            'cpf' => '12345678900',  // Alinhar com o CPF que foi enviado
        ]);
    }
}
