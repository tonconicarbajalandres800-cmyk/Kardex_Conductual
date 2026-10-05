<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            if (!Schema::hasColumn('asistencias', 'estudiante_id')) {
                $table->foreignId('estudiante_id')->nullable()->constrained('estudiantes')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropForeign(['estudiante_id']);
            $table->dropColumn('estudiante_id');
        });
    }
};