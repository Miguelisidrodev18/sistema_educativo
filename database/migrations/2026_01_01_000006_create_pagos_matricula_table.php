<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pagos_matricula', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->decimal('monto_matricula', 8, 2)->default(0);
            $table->decimal('pension_mensual', 8, 2)->default(0);
            $table->unsignedTinyInteger('numero_pensiones')->default(10);
            $table->enum('estado_pago', ['PENDIENTE','PAGADO','PARCIAL','VENCIDO'])->default('PENDIENTE');
            $table->string('metodo_pago', 30)->nullable();
            $table->string('numero_operacion', 60)->nullable();
            $table->string('comprobante_url')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pagos_matricula'); }
};
