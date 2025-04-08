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
            'name' => 'John Doe',
            'cpf' => '12345678901',
        ]);

        $response->assertFound(); // Eu sei que o certo seria 401, mas o Laravel só responde 302.  ¯\_(ツ)_/¯
    }

    public function test_authenticated_user_can_store_patient()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('patients.store'), [
            'name' => 'John Doe',
            'cpf' => '12345678901',
            'birth_date' => '1990-01-01',
            'phone' => '1234567890',
            'email' => 'john@example.com',
            'address' => '123 Main St',
            'insurance' => 'Nenhum',
            'is_active' => true,
        ]);

        $response->assertFound(); // Verifica se a criação redireciona
        $this->assertDatabaseHas('patients', [
            'name' => 'John Doe',
            'cpf' => '12345678901',
        ]);
    }
}
