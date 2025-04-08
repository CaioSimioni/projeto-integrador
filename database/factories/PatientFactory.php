<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'cpf' => $this->faker->unique()->numerify('###########'), // CPF com 11 dígitos
            'birth_date' => $this->faker->date,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
            'insurance' => $this->faker->randomElement(['Nenhum', 'São Francisco', 'Unimed']),
            'is_active' => $this->faker->boolean,
        ];
    }
}
