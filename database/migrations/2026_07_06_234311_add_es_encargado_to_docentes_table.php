<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('docentes', 'es_encargado')) {
            Schema::table('docentes', function (Blueprint $table) {
                $table->boolean('es_encargado')->default(false);
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('docentes', 'es_encargado')) {
            Schema::table('docentes', function (Blueprint $table) {
                $table->dropColumn('es_encargado');
            });
        }
    }
};