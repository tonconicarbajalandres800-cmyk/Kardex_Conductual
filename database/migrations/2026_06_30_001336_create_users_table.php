<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->string('apellidos');
        $table->string('ci')->unique();
        $table->enum('rol', ['admin', 'docente', 'padre'])->default('padre');
        $table->string('curso')->nullable();      // solo para estudiantes
        $table->string('materia')->nullable();    // solo para docentes
        $table->string('telefono');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->string('foto')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
