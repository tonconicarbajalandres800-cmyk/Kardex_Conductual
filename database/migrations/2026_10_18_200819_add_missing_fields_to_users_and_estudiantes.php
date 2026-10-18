<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Agregar direccion a users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'direccion')) {
                $table->string('direccion')->nullable()->after('telefono');
            }
        });

        // 2. Agregar direccion y numero_lista a estudiantes + hacer fecha_nacimiento nullable
        Schema::table('estudiantes', function (Blueprint $table) {
            if (!Schema::hasColumn('estudiantes', 'direccion')) {
                $table->string('direccion')->nullable()->after('padre_id');
            }
            if (!Schema::hasColumn('estudiantes', 'numero_lista')) {
                $table->integer('numero_lista')->nullable()->after('curso_id');
            }
            
            // Hacer fecha_nacimiento nullable (si ya existe, esto podría fallar, lo manejamos)
            $table->date('fecha_nacimiento')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'direccion')) {
                $table->dropColumn('direccion');
            }
        });

        Schema::table('estudiantes', function (Blueprint $table) {
            if (Schema::hasColumn('estudiantes', 'direccion')) {
                $table->dropColumn('direccion');
            }
            if (Schema::hasColumn('estudiantes', 'numero_lista')) {
                $table->dropColumn('numero_lista');
            }
        });
    }
};