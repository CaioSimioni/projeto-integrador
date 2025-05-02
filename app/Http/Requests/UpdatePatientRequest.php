<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'full_name' => 'sometimes|string|max:255',
            'cpf' => 'sometimes|string|size:11|unique:patients,cpf,' . $this->patient->id,
            'birth_date' => 'sometimes|date|before_or_equal:today',
            'gender' => 'sometimes|string|in:male,female,other',
            'mother_name' => 'sometimes|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'sus_number' => 'nullable|string|max:15|regex:/^\d{15}$/',
            'medical_record' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:100',
            'birth_place' => 'nullable|string|max:100',
            'state' => 'nullable|string|size:2|in:AC,AL,AP,AM,BA,CE,DF,ES,GO,MA,MT,MS,MG,PA,PB,PR,PE,PI,RJ,RN,RS,RO,RR,SC,SP,SE,TO',
            'cep' => 'nullable|string|regex:/^\d{8}$/',
            'address' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:10',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state_address' => 'nullable|string|size:2|in:AC,AL,AP,AM,BA,CE,DF,ES,GO,MA,MT,MS,MG,PA,PB,PR,PE,PI,RJ,RN,RS,RO,RR,SC,SP,SE,TO',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|regex:/^\d{10,11}$/',
            'is_active' => 'sometimes|boolean',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->cpf) {
            $this->merge(['cpf' => preg_replace('/\D/', '', $this->cpf)]);
        }

        if ($this->phone) {
            $this->merge(['phone' => preg_replace('/\D/', '', $this->phone)]);
        }

        if ($this->cep) {
            $this->merge(['cep' => preg_replace('/\D/', '', $this->cep)]);
        }
    }
}
