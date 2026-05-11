<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 15)->unique();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['MASCULINO','FEMENINO'])->nullable();
            $table->string('ciudad', 80)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->enum('nivel_academico', ['INICIAL','PRIMARIA','SECUNDARIA'])->nullable();
            $table->string('grado_seccion', 50)->nullable();
            $table->boolean('repitencia')->default(false);
            $table->string('foto_path')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->foreignId('apoderado_id')->nullable()->constrained('apoderados')->nullOnDelete();
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete();
            $table->enum('tipo_descuento', ['ninguno','hermanos','beca','otro'])->default('ninguno');
            $table->decimal('monto_descuento', 8, 2)->default(0);
            $table->string('descripcion_descuento')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('alumnos'); }
};
