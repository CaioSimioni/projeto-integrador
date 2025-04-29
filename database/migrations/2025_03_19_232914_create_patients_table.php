<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('cpf')->unique();
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('mother_name');
            $table->string('father_name')->nullable();
            $table->string('sus_number')->nullable();
            $table->string('medical_record')->nullable();
            $table->string('nationality');
            $table->string('birth_place');
            $table->string('state', 2); // UF de nascimento
            $table->string('cep')->nullable();
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->string('state_address', 2)->nullable(); // UF do endereço
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
        
    }

    public function down(): void {
        Schema::dropIfExists('patients');
    }
};
