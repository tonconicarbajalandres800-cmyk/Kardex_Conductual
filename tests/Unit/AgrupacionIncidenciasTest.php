<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class AgrupacionIncidenciasTest extends TestCase
{
    public function test_agrupar_incidencias_por_gravedad(): void
    {
        $incidencias = [
            ['tipo' => 'Llegada tarde', 'gravedad' => 'Leve'],
            ['tipo' => 'Falta de respeto', 'gravedad' => 'Grave'],
            ['tipo' => 'No traer materiales', 'gravedad' => 'Leve'],
            ['tipo' => 'Comportamiento inapropiado', 'gravedad' => 'Moderada'],
            ['tipo' => 'No cumplir tareas', 'gravedad' => 'Leve'],
        ];

        $leves = array_filter($incidencias, function($inc) {
            return $inc['gravedad'] === 'Leve';
        });

        $graves = array_filter($incidencias, function($inc) {
            return $inc['gravedad'] === 'Grave';
        });

        // Reindexar los arrays para que los índices comiencen desde 0
        $leves = array_values($leves);
        $graves = array_values($graves);

        $this->assertCount(3, $leves);
        $this->assertCount(1, $graves);
        $this->assertEquals('Leve', $leves[0]['gravedad']);
        $this->assertEquals('Grave', $graves[0]['gravedad']);
    }
}