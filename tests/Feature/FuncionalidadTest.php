<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FuncionalidadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * T-01: Login con credenciales correctas.
     */
    public function test_login_correcto()
    {
        $password = 'password123';

        $user = User::factory()->create([
            'email'    => 'login_correcto_' . uniqid() . '@example.com',
            'password' => bcrypt($password),
            'rol'      => 'admin',
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => $password,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * T-02: Login con credenciales incorrectas.
     */
    public function test_login_incorrecto()
    {
        $user = User::factory()->create([
            'email'    => 'login_incorrecto_' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
            'rol'      => 'admin',
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password_equivocada',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * T-03: Cerrar sesión.
     */
    public function test_cerrar_sesion()
    {
        $user = User::factory()->create([
            'email' => 'cerrar_sesion_' . uniqid() . '@example.com',
            'rol'   => 'admin',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * DataProvider: combinaciones rol → ruta esperada.
     */
    public static function rolesProvider(): array
    {
        return [
            'admin'      => ['admin',      'admin.dashboard'],
            'docente'    => ['docente',    'docente.dashboard'],
            'padre'      => ['padre',      'padre.dashboard'],
            'regente'    => ['regente',    'regente.dashboard'],
          //  'estudiante' => ['estudiante', 'estudiante.dashboard'],
        ];
    }

    /**
     * T-04: Login redirige al dashboard correcto según el rol.
     */
    #[DataProvider('rolesProvider')]
    public function test_login_redirige_segun_rol(string $rol, string $rutaEsperada)
    {
        $password = 'password123';

        $user = User::factory()->create([
            'email'    => 'rol_' . $rol . '_' . uniqid() . '@example.com',
            'password' => bcrypt($password),
            'rol'      => $rol,
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => $password,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route($rutaEsperada));
    }
}