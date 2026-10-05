<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class KardexIndividualTest extends TestCase
{
    public function test_crear_kardex_estudiante(): void
    {
        $estudiante = [
            'nombre' => 'Juan Pérez',
            'curso' => '5to Secundaria',
            'incidencias' => [
                ['fecha' => '2026-07-15', 'tipo' => 'Llegada tarde'],
                ['fecha' => '2026-07-18', 'tipo' => 'No traer materiales'],
            ]
        ];

        $this->assertArrayHasKey('nombre', $estudiante);
        $this->assertArrayHasKey('curso', $estudiante);
        $this->assertArrayHasKey('incidencias', $estudiante);
        $this->assertCount(2, $estudiante['incidencias']);
        $this->assertEquals('Juan Pérez', $estudiante['nombre']);
    }

    public function test_agregar_incidencia_al_kardex(): void
    {
        $kardex = [
            'estudiante' => 'María López',
            'incidencias' => []
        ];

        // Agregar una incidencia
        $nuevaIncidencia = [
            'fecha' => '2026-07-20',
            'tipo' => 'Falta de respeto',
            'descripcion' => 'Respondió de manera inapropiada al profesor'
        ];
        
        $kardex['incidencias'][] = $nuevaIncidencia;

        $this->assertCount(1, $kardex['incidencias']);
        $this->assertEquals('Falta de respeto', $kardex['incidencias'][0]['tipo']);
    }
}