<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check(); // Apenas usuários autenticados podem fazer a requisição
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255', // Campo obrigatório
            'cpf' => 'required|string|unique:patients,cpf|max:14',
            'birth_date' => 'required|date',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'insurance' => 'nullable|in:Nenhum,São Francisco,Unimed',
            'is_active' => 'nullable|boolean',
        ];
    }
}
