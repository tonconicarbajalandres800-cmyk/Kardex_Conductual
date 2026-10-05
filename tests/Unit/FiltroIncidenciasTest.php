<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FiltroIncidenciasTest extends TestCase
{
    public function test_filtrar_incidencias_por_fecha(): void
    {
        $incidencias = [
            ['fecha' => '2026-07-10', 'descripcion' => 'Llegada tarde'],
            ['fecha' => '2026-07-15', 'descripcion' => 'No traer materiales'],
            ['fecha' => '2026-07-20', 'descripcion' => 'Falta de respeto'],
            ['fecha' => '2026-07-25', 'descripcion' => 'Comportamiento inapropiado'],
        ];

        $fechaInicio = '2026-07-15';
        $fechaFin = '2026-07-22';

        $incidenciasFiltradas = array_filter($incidencias, function($inc) use ($fechaInicio, $fechaFin) {
            return $inc['fecha'] >= $fechaInicio && $inc['fecha'] <= $fechaFin;
        });

        // Reindexar el array para que los índices comiencen desde 0
        $incidenciasFiltradas = array_values($incidenciasFiltradas);

        $this->assertCount(2, $incidenciasFiltradas);
        $this->assertEquals('No traer materiales', $incidenciasFiltradas[0]['descripcion']);
        $this->assertEquals('Falta de respeto', $incidenciasFiltradas[1]['descripcion']);
    }
}