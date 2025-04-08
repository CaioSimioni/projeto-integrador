<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_be_created()
    {
        $patient = Patient::factory()->create([
            'name' => 'John Doe',
            'cpf' => '12345678901',
        ]);

        $this->assertDatabaseHas('patients', [
            'name' => 'John Doe',
            'cpf' => '12345678901',
        ]);
    }

    public function test_patient_requires_name_and_cpf()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Patient::create([]); // Tenta criar um paciente sem dados
    }

    public function test_cpf_must_be_unique()
    {
        Patient::factory()->create(['cpf' => '12345678901']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Patient::factory()->create(['cpf' => '12345678901']); // Tenta criar um paciente com o mesmo CPF
    }
}
