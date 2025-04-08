<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials_test';

    protected $fillable = ['name', 'type', 'quantity', 'expiration_date', 'description'];
}
