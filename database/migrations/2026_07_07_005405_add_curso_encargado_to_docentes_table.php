<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('docentes', 'curso_encargado_id')) {
            Schema::table('docentes', function (Blueprint $table) {
                $table->foreignId('curso_encargado_id')->nullable()->after('es_encargado')->constrained('cursos')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('docentes', 'curso_encargado_id')) {
            Schema::table('docentes', function (Blueprint $table) {
                $table->dropForeign(['curso_encargado_id']);
                $table->dropColumn('curso_encargado_id');
            });
        }
    }
};