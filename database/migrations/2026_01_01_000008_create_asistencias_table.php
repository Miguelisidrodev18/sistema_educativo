<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->date('fecha');
            $table->enum('estado', ['PRESENTE','AUSENTE','TARDANZA','JUSTIFICADO'])->default('PRESENTE');
            $table->time('hora_registro')->nullable();
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete();
            $table->timestamps();
            $table->unique(['alumno_id','fecha']);
        });
        Schema::create('asistencias_docentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('fecha');
            $table->enum('estado', ['PRESENTE','AUSENTE','TARDANZA','JUSTIFICADO'])->default('PRESENTE');
            $table->time('hora_registro')->nullable();
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete();
            $table->timestamps();
            $table->unique(['user_id','fecha']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('asistencias_docentes');
        Schema::dropIfExists('asistencias');
    }
};
