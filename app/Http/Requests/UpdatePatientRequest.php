<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check(); // Retorna true apenas se o usuário estiver autenticado
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'cpf' => 'sometimes|string|unique:patients,cpf,' . $this->patient->id,
            'birth_date' => 'sometimes|date',
            'phone' => 'sometimes|string',
            'email' => 'sometimes|email|unique:patients,email,' . $this->patient->id,
            'address' => 'sometimes|string',
            'insurance' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
