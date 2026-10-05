<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class MiSegundaPruebaTest extends TestCase
{
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_suma_basica(): void
    {
        $resultado = 2 + 2;
        $this->assertEquals(4, $resultado);
        $this->assertIsInt($resultado);
    }

    public function test_arrays(): void
    {
        $array = [1, 2, 3];
        $this->assertCount(3, $array);
        $this->assertContains(2, $array);
    }
}