<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            // Verificar si la columna no existe antes de agregarla
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

    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropColumn(['fecha', 'estado', 'motivo', 'registrado_por']);
        });
    }
};