<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_patients_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('patients.index'));

        $response->assertStatus(200); // Verifica se o usuário autenticado pode acessar a rota
    }

    public function test_guest_cannot_access_patients_index()
    {
        $response = $this->get(route('patients.index'));

        $response->assertRedirect(route('login')); // Verifica se o guest é redirecionado para o login
    }

    public function test_authenticated_user_can_access_appointments_index()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('appointments.index'));

        $response->assertStatus(200); // Verifica se o usuário autenticado pode acessar a rota
    }

    public function test_guest_cannot_access_appointments_index()
    {
        $response = $this->get(route('appointments.index'));

        $response->assertRedirect(route('login')); // Verifica se o guest é redirecionado para o login
    }
}
