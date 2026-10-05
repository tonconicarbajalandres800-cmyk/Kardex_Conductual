<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ConteoIncidenciasTest extends TestCase
{
    public function test_contar_incidencias_por_estudiante(): void
    {
        $incidencias = [
            ['estudiante' => 'Juan Pérez', 'tipo' => 'Llegada tarde'],
            ['estudiante' => 'María López', 'tipo' => 'Falta de respeto'],
            ['estudiante' => 'Juan Pérez', 'tipo' => 'No traer materiales'],
            ['estudiante' => 'Pedro Gómez', 'tipo' => 'Comportamiento inapropiado'],
            ['estudiante' => 'Juan Pérez', 'tipo' => 'No cumplir tareas'],
        ];

        $incidenciasJuan = array_filter($incidencias, function($inc) {
            return $inc['estudiante'] === 'Juan Pérez';
        });

        $this->assertCount(3, $incidenciasJuan);
        $this->assertEquals(3, count($incidenciasJuan));
    }

    public function test_contar_incidencias_por_tipo(): void
    {
        $incidencias = [
            ['tipo' => 'Llegada tarde'],
            ['tipo' => 'Falta de respeto'],
            ['tipo' => 'Llegada tarde'],
            ['tipo' => 'Llegada tarde'],
            ['tipo' => 'No traer materiales'],
        ];

        $llegadasTarde = array_filter($incidencias, function($inc) {
            return $inc['tipo'] === 'Llegada tarde';
        });

        $this->assertCount(3, $llegadasTarde);
        $this->assertEquals(3, count($llegadasTarde));
    }
}