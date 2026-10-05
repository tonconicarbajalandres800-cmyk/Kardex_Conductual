<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('incidencias', function (Blueprint $table) {
        $table->id();
        $table->foreignId('estudiante_id')->constrained('estudiantes');
        $table->foreignId('docente_id')->constrained('docentes');
        $table->string('tipo');
        $table->string('materia')->nullable();
        $table->text('descripcion')->nullable();
        $table->timestamp('fecha_hora');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};
