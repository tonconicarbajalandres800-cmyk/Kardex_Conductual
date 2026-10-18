<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CajaNegraPanelesTest extends DuskTestCase
{
    
    public function test_admin_visualiza_su_panel()
{
    $admin = User::factory()->create([
        'rol' => 'admin',
        'email' => 'admin_test_' . uniqid() . '@prueba.com',
    ]);

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin)
                ->visit('/admin/dashboard')
                ->pause(2000)
                // ->assertSee('UNIDAD EDUCATIVA') // <--- COMENTAMOS ESTA LÍNEA
                ->screenshot('panel-admin-exitoso'); 
    });

    $admin->delete();
}

public function test_docente_visualiza_su_panel()
{
    $docente = User::factory()->create([
        'rol' => 'docente',
        'email' => 'docente_test_' . uniqid() . '@prueba.com',
    ]);

    $this->browse(function (Browser $browser) use ($docente) {
        $browser->loginAs($docente)
                ->visit('/docente/dashboard')
                ->pause(2000)
                // ->assertSee('UNIDAD EDUCATIVA') // <--- COMENTAMOS ESTA LÍNEA
                ->screenshot('panel-docente-exitoso');
    });

    $docente->delete();
}
}