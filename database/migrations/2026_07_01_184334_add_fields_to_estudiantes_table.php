<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('docentes', function (Blueprint $table) {
            // Solo agregar si no existe
            if (!Schema::hasColumn('docentes', 'es_encargado')) {
                $table->boolean('es_encargado')->default(false)->after('email');
            }
            
            if (!Schema::hasColumn('docentes', 'item')) {
                $table->string('item')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('docentes', 'curso_encargado_id')) {
                $table->foreignId('curso_encargado_id')->nullable()->constrained('cursos')->after('es_encargado');
            }
        });
    }

    public function down()
    {
        Schema::table('docentes', function (Blueprint $table) {
            if (Schema::hasColumn('docentes', 'curso_encargado_id')) {
                $table->dropForeign(['curso_encargado_id']);
                $table->dropColumn('curso_encargado_id');
            }
            if (Schema::hasColumn('docentes', 'item')) {
                $table->dropColumn('item');
            }
            if (Schema::hasColumn('docentes', 'es_encargado')) {
                $table->dropColumn('es_encargado');
            }
        });
    }
};