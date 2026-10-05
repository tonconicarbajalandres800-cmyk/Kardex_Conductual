<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Citaciones
        Schema::table('citaciones', function (Blueprint $table) {
            $table->dropForeign(['estudiante_id']);
            $table->foreign('estudiante_id')
                  ->references('id')
                  ->on('estudiantes')
                  ->onDelete('cascade');
        });

        // 2. Incidencias
        Schema::table('incidencias', function (Blueprint $table) {
            $table->dropForeign(['estudiante_id']);
            $table->foreign('estudiante_id')
                  ->references('id')
                  ->on('estudiantes')
                  ->onDelete('cascade');
        });

        // 3. Notificaciones (si existe)
        if (Schema::hasTable('notificaciones')) {
            Schema::table('notificaciones', function (Blueprint $table) {
                $table->dropForeign(['estudiante_id']);
                $table->foreign('estudiante_id')
                      ->references('id')
                      ->on('estudiantes')
                      ->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::table('citaciones', function (Blueprint $table) {
            $table->dropForeign(['estudiante_id']);
            $table->foreign('estudiante_id')
                  ->references('id')
                  ->on('estudiantes');
        });

        Schema::table('incidencias', function (Blueprint $table) {
            $table->dropForeign(['estudiante_id']);
            $table->foreign('estudiante_id')
                  ->references('id')
                  ->on('estudiantes');
        });

        if (Schema::hasTable('notificaciones')) {
            Schema::table('notificaciones', function (Blueprint $table) {
                $table->dropForeign(['estudiante_id']);
                $table->foreign('estudiante_id')
                      ->references('id')
                      ->on('estudiantes');
            });
        }
    }
};