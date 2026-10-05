<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\IncidenciaRegistrada;
use App\Listeners\VerificarFaltasYGenerarCitacion;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    // EGIRSTRAR EL EVENTO Y LISTENER

    public function boot(): void
    {
        
        Event::listen(
            IncidenciaRegistrada::class,
            VerificarFaltasYGenerarCitacion::class
        );
    }
}