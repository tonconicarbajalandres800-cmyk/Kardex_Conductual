<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class FuncionalidadTest extends TestCase
{
    use RefreshDatabase;

    /** T-01: Login con credenciales correctas */
    public function test_login_correcto()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'password123',
        ]);

        // Verifica que no hubo error (código 500)
        $this->assertNotEquals(500, $response->status());
        
        // Verifica que no hubo errores de validación
        $response->assertSessionHasNoErrors();
    }

    /** T-02: Login con credenciales incorrectas */
    public function test_login_incorrecto()
    {
        $response = $this->post('/login', [
            'email' => 'noexiste@test.com',
            'password' => 'wrong',
        ]);

        $this->assertGuest();
    }

    /** T-03: Cerrar sesión */
    public function test_cerrar_sesion()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
    }
}