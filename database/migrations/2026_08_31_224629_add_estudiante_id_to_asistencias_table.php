<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('asistencias', function (Blueprint $table) {
            if (!Schema::hasColumn('asistencias', 'estudiante_id')) {
                $table->foreignId('estudiante_id')->nullable()->constrained('estudiantes')->onDelete('cascade');
            }
            if (!Schema::hasColumn('asistencias', 'curso_id')) {
                $table->foreignId('curso_id')->nullable()->constrained('cursos')->onDelete('cascade');
            }
            if (!Schema::hasColumn('asistencias', 'fecha')) {
                $table->date('fecha')->nullable();
            }
            if (!Schema::hasColumn('asistencias', 'estado')) {
                $table->string('estado')->default('presente');
            }
            if (!Schema::hasColumn('asistencias', 'motivo')) {
                $table->string('motivo')->nullable();
            }
            if (!Schema::hasColumn('asistencias', 'registrado_por')) {
                $table->string('registrado_por')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropColumn(['estudiante_id', 'curso_id', 'fecha', 'estado', 'motivo', 'registrado_por']);
        });
    }
};