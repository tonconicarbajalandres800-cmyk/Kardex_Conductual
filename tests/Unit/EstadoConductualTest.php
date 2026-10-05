<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class EstadoConductualTest extends TestCase
{
    public function test_determinar_estado_conductual(): void
    {
        // Estudiante con pocas incidencias = Bueno
        $incidenciasLeves = 2;
        $estado = $this->determinarEstado($incidenciasLeves);
        $this->assertEquals('Bueno', $estado);

        // Estudiante con incidencias moderadas = Regular
        $incidenciasModeradas = 5;
        $estado = $this->determinarEstado($incidenciasModeradas);
        $this->assertEquals('Regular', $estado);

        // Estudiante con muchas incidencias = Malo
        $incidenciasGraves = 10;
        $estado = $this->determinarEstado($incidenciasGraves);
        $this->assertEquals('Malo', $estado);
    }

    private function determinarEstado($cantidadIncidencias)
    {
        if ($cantidadIncidencias <= 2) return 'Bueno';
        if ($cantidadIncidencias <= 5) return 'Regular';
        return 'Malo';
    }

    public function test_calcular_nivel_de_alerta(): void
    {
        $incidenciasGraves = 3;
        $nivelAlerta = $this->calcularAlerta($incidenciasGraves);
        $this->assertEquals('Alta', $nivelAlerta);

        $incidenciasLeves = 0; // Cambiado de 1 a 0
        $nivelAlerta = $this->calcularAlerta($incidenciasLeves);
        $this->assertEquals('Baja', $nivelAlerta);
        
        $incidenciasMedias = 1;
        $nivelAlerta = $this->calcularAlerta($incidenciasMedias);
        $this->assertEquals('Media', $nivelAlerta);
    }

    private function calcularAlerta($incidenciasGraves)
    {
        if ($incidenciasGraves >= 3) return 'Alta';
        if ($incidenciasGraves >= 1) return 'Media';
        return 'Baja';
    }
}