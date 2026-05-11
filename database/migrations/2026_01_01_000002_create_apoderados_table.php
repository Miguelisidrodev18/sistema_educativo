<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('apoderados', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 15)->nullable();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('telefono', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('parentesco', 50)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('apoderados'); }
};
