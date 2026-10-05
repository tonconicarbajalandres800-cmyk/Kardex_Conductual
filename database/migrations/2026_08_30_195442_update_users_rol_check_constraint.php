<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // SQLite no soporta DROP CONSTRAINT, así que lo saltamos
        if (DB::getDriverName() !== 'sqlite') {
            // Eliminar la restricción CHECK existente
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_rol_check');
            
            // Crear la nueva restricción CHECK con 'regente' incluido
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_rol_check CHECK (rol IN ('admin', 'docente', 'padre', 'estudiante', 'regente'))");
        }
    }

    public function down()
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_rol_check');
            
            // Restaurar la restricción original (sin regente)
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_rol_check CHECK (rol IN ('admin', 'docente', 'padre', 'estudiante'))");
        }
    }
};