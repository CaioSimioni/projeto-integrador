<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Permitir que qualquer usuário autenticado envie o formulário
    }

    public function rules()
    {
        return [
            'fullName' => 'required|string|max:255',
            'cpf' => 'required|string|size:11|unique:patients,cpf',
            'birthDate' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
            'motherName' => 'required|string|max:255',
            'fatherName' => 'nullable|string|max:255',
            'susNumber' => 'nullable|string|max:15',
            'medicalRecord' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:100',
            'birthPlace' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:9',
            'address' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:10',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state_address' => 'nullable|string|max:2',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:15',
        ];
    }
}
