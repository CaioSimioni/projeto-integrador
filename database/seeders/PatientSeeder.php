<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::factory()->create([
            'name' => 'John Doe',
            'cpf' => '123.456.789-00',
            'birth_date' => '1990-01-01',
            'phone' => '(11) 98765-4321',
            'email' => 'johndoe@example.com',
            'address' => '123 Main Street, City, State, ZIP',
            'insurance' => 'Health Insurance Co.',
            'is_active' => true,
        ]);

        Patient::factory()->create([
            'name' => 'Jane Smith',
            'cpf' => '987.654.321-00',
            'birth_date' => '1985-05-15',
            'phone' => '(21) 91234-5678',
            'email' => 'janesmith@example.com',
            'address' => '456 Elm Street, City, State, ZIP',
            'insurance' => 'Premium Health Co.',
            'is_active' => true,
        ]);

        Patient::factory()->create([
            'name' => 'Alice Johnson',
            'cpf' => '111.222.333-44',
            'birth_date' => '1992-07-20',
            'phone' => '(31) 92345-6789',
            'email' => 'alicejohnson@example.com',
            'address' => '789 Oak Avenue, City, State, ZIP',
            'insurance' => 'Basic Health Plan',
            'is_active' => true,
        ]);

        Patient::factory()->create([
            'name' => 'Bob Brown',
            'cpf' => '555.666.777-88',
            'birth_date' => '1980-03-10',
            'phone' => '(41) 93456-7890',
            'email' => 'bobbrown@example.com',
            'address' => '321 Pine Road, City, State, ZIP',
            'insurance' => 'Standard Health Co.',
            'is_active' => true,
        ]);

        Patient::factory()->create([
            'name' => 'Charlie Davis',
            'cpf' => '999.888.777-66',
            'birth_date' => '1995-12-25',
            'phone' => '(51) 94567-8901',
            'email' => 'charliedavis@example.com',
            'address' => '654 Cedar Lane, City, State, ZIP',
            'insurance' => 'Health Plus Co.',
            'is_active' => true,
        ]);

        Patient::factory()->create([
            'name' => 'Diana Evans',
            'cpf' => '222.333.444-55',
            'birth_date' => '1988-09-09',
            'phone' => '(61) 95678-9012',
            'email' => 'dianaevans@example.com',
            'address' => '987 Maple Street, City, State, ZIP',
            'insurance' => 'Family Health Plan',
            'is_active' => true,
        ]);

        Patient::factory()->create([
            'name' => 'Ethan Foster',
            'cpf' => '444.555.666-77',
            'birth_date' => '1993-11-11',
            'phone' => '(71) 96789-0123',
            'email' => 'ethanfoster@example.com',
            'address' => '123 Birch Boulevard, City, State, ZIP',
            'insurance' => 'Comprehensive Health Co.',
            'is_active' => true,
        ]);

        Patient::factory()->create([
            'name' => 'Fiona Green',
            'cpf' => '777.888.999-00',
            'birth_date' => '1982-06-06',
            'phone' => '(81) 97890-1234',
            'email' => 'fionagreen@example.com',
            'address' => '456 Willow Way, City, State, ZIP',
            'insurance' => 'Elite Health Plan',
            'is_active' => true,
        ]);

        Patient::factory()->create([
            'name' => 'George Harris',
            'cpf' => '333.444.555-66',
            'birth_date' => '1998-04-04',
            'phone' => '(91) 98901-2345',
            'email' => 'georgeharris@example.com',
            'address' => '789 Spruce Drive, City, State, ZIP',
            'insurance' => 'Affordable Health Co.',
            'is_active' => true,
        ]);

        Patient::factory()->create([
            'name' => 'Hannah White',
            'cpf' => '666.777.888-99',
            'birth_date' => '1987-08-08',
            'phone' => '(11) 99012-3456',
            'email' => 'hannahwhite@example.com',
            'address' => '321 Aspen Court, City, State, ZIP',
            'insurance' => 'Health Secure Co.',
            'is_active' => true,
        ]);
    }
}
