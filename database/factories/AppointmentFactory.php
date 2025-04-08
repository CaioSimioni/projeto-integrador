<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        return [
            'patient_id' => \App\Models\Patient::factory(),
            'appointment_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'notes' => $this->faker->sentence,
        ];
    }
}
