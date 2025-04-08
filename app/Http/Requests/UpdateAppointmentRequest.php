<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check(); // Retorna true apenas se o usuário estiver autenticado
    }

    public function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }
}
