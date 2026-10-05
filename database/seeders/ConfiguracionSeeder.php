<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('configuraciones')->insert([
            ['clave' => 'fondo_login', 'valor' => 'default.jpg'],
            ['clave' => 'nombre_colegio', 'valor' => 'U.E. Gral. José de San Martín'],
        ]);
    }
}