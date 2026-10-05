<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('citaciones', function (Blueprint $table) {
        $table->dropForeign(['docente_id']);
        $table->foreign('docente_id')->references('id')->on('docentes')->onDelete('cascade');
    });

    Schema::table('incidencias', function (Blueprint $table) {
        $table->dropForeign(['docente_id']);
        $table->foreign('docente_id')->references('id')->on('docentes')->onDelete('cascade');
    });
}
};
