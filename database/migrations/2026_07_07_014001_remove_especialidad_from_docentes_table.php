<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    if (Schema::hasColumn('docentes', 'especialidad')) {
        Schema::table('docentes', function (Blueprint $table) {
            $table->dropColumn('especialidad');
        });
    }
}

public function down()
{
    if (!Schema::hasColumn('docentes', 'especialidad')) {
        Schema::table('docentes', function (Blueprint $table) {
            $table->string('especialidad')->nullable();
        });
    }
}
};