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
        $gender = $this->faker->randomElement(['male', 'female', 'other']);

        return [
            'full_name' => $this->faker->name,
            'cpf' => $this->faker->unique()->numerify('###########'),
            'birth_date' => $this->faker->date,
            'gender' => $gender,
            'mother_name' => $this->faker->name('female'),
            'father_name' => $this->faker->optional()->name('male'),
            'sus_number' => $this->faker->optional()->numerify('############'),
            'medical_record' => $this->faker->optional()->bothify('MR-####-??'),
            'nationality' => 'Brasileiro',
            'birth_place' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
            'cep' => $this->faker->numerify('########'),
            'address' => $this->faker->streetAddress,
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->optional()->secondaryAddress,
            'neighborhood' => $this->faker->citySuffix,
            'city' => $this->faker->city,
            'state_address' => $this->faker->stateAbbr,
            'country' => 'Brasil',
            'phone' => $this->faker->phoneNumber,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Patient $patient) {
            // Logic to execute after creating the model instance but before persisting it.
            if (empty($patient->sus_number)) {
                $patient->sus_number = $this->faker->numerify('############');
            }
        })->afterCreating(function (Patient $patient) {
            // Logic to execute after the model instance is saved to the database.
            if (empty($patient->medical_record)) {
                $patient->medical_record = 'MR-' . str_pad($patient->id, 4, '0', STR_PAD_LEFT) . '-' . strtoupper($this->faker->lexify('??'));
                $patient->save();
            }
        });
    }
}
