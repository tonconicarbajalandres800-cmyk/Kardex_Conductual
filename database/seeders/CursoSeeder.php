<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        // Nombres de los cursos del 1ro al 6to
        $nombresCursos = [
            1 => 'Primero de Secundaria',
            2 => 'Segundo de Secundaria',
            3 => 'Tercero de Secundaria',
            4 => 'Cuarto de Secundaria',
            5 => 'Quinto de Secundaria',
            6 => 'Sexto de Secundaria',
        ];

        $paralelos = ['A', 'B', 'C'];

        // Recorremos cada nivel (del 1 al 6)
        foreach ($nombresCursos as $nivel => $nombre) {
            // Recorremos cada paralelo (A, B, C)
            foreach ($paralelos as $paralelo) {
                Curso::create([
                    'nombre'   => $nombre,
                    'paralelo' => $paralelo,
                    'nivel'    => $nivel,
                    'gestion'  => '2026', // Cambia el año si es necesario
                ]);
            }
        }
    }
}