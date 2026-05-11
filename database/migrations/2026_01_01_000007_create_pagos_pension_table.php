<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pagos_pension', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->string('mes_pagado', 20);
            $table->year('anio');
            $table->decimal('monto', 8, 2);
            $table->string('metodo_pago', 30)->default('EFECTIVO');
            $table->string('numero_recibo', 60)->nullable();
            $table->dateTime('fecha_pago');
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete();
            $table->timestamps();
            $table->unique(['alumno_id','mes_pagado','anio'], 'unique_pension_mes');
        });
    }
    public function down(): void { Schema::dropIfExists('pagos_pension'); }
};
