<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('citaciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('estudiante_id')->constrained('estudiantes');
        $table->foreignId('docente_id')->constrained('docentes');
        $table->timestamp('fecha_programada');
        $table->text('motivo');
        $table->enum('estado', ['pendiente', 'confirmada', 'reprogramada', 'justificada', 'cancelada'])->default('pendiente');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citaciones');
    }
};
