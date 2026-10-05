<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class IncidenciasTest extends TestCase
{
    public function test_registrar_incidencia(): void
    {
        $incidencia = [
            'estudiante' => 'Juan Pérez',
            'fecha' => '2026-07-20',
            'tipo' => 'Llegada tarde',
            'descripcion' => 'Llegó 15 minutos después del inicio',
            'gravedad' => 'Leve'
        ];

        $this->assertArrayHasKey('estudiante', $incidencia);
        $this->assertArrayHasKey('tipo', $incidencia);
        $this->assertArrayHasKey('gravedad', $incidencia);
        $this->assertEquals('Juan Pérez', $incidencia['estudiante']);
        $this->assertEquals('Leve', $incidencia['gravedad']);
    }

    public function test_validar_tipos_incidencia(): void
    {
        $tiposValidos = [
            'Llegada tarde',
            'Falta de respeto',
            'No traer materiales',
            'Comportamiento inapropiado',
            'No cumplir tareas'
        ];

        $tipoPrueba = 'Llegada tarde';
        $this->assertContains($tipoPrueba, $tiposValidos);

        $tipoInvalido = 'Nota baja';
        $this->assertNotContains($tipoInvalido, $tiposValidos);
    }

    public function test_validar_gravedad_incidencia(): void
    {
        $gravedades = ['Leve', 'Moderada', 'Grave'];

        $this->assertContains('Leve', $gravedades);
        $this->assertContains('Moderada', $gravedades);
        $this->assertContains('Grave', $gravedades);
        $this->assertCount(3, $gravedades);
    }
}