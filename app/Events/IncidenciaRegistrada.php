<?php

namespace App\Events;

use App\Models\Incidencia;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncidenciaRegistrada
{
    use Dispatchable, SerializesModels;

    public $incidencia;

    public function __construct(Incidencia $incidencia)
    {
        $this->incidencia = $incidencia;
    }
}





