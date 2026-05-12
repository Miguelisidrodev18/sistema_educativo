<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('docente_asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete();
            $table->string('nivel', 20);          // inicial | primaria | secundaria
            $table->string('grado_seccion', 30);  // 3 AÑOS, PRIMERO, SEGUNDO A, etc.
            $table->string('materia', 60)->nullable(); // null = tutor/auxiliar del aula
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docente_asignaciones');
    }
};
