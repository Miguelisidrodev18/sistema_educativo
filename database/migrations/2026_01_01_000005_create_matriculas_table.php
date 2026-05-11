<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_matricula', 30)->unique();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->year('periodo');
            $table->enum('nivel_academico', ['INICIAL','PRIMARIA','SECUNDARIA']);
            $table->string('grado_seccion', 50);
            $table->enum('situacion', ['ALUMNO NUEVO','TRASLADADO','REPITENTE'])->default('ALUMNO NUEVO');
            $table->enum('modalidad_pago', ['contado','mensual'])->default('mensual');
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete();
            $table->enum('estado', ['activo','inactivo'])->default('activo');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('matriculas'); }
};
