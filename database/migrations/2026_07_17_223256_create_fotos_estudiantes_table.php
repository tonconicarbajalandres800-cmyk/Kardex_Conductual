<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fotos_estudiantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id');
            $table->string('nombre_archivo');
            $table->string('ruta');
            $table->timestamps();
            
            $table->foreign('estudiante_id')
                  ->references('id')
                  ->on('estudiantes')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('fotos_estudiantes');
    }
};