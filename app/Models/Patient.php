<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'cpf',
        'birth_date',
        'gender',
        'mother_name',
        'father_name',
        'sus_number',
        'medical_record',
        'nationality',
        'birth_place',
        'state',
        'cep',
        'address',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state_address',
        'country',
        'phone',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Já deixa o esquema preparado pro relacionamento com exames
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
