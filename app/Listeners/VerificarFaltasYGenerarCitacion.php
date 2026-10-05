<?php

namespace App\Listeners;

use App\Events\IncidenciaRegistrada;
use App\Models\Citacion;
use App\Models\Incidencia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Jobs\EnviarNotificacionMetaWhatsApp;

class VerificarFaltasYGenerarCitacion
{
    public function handle(IncidenciaRegistrada $event)
    {
        Log::info('🚀 LISTENER EJECUTADO - Incidencia ID: ' . $event->incidencia->id);
        
        $incidencia = $event->incidencia;
        
        if (!$incidencia->estudiante) {
            Log::warning('Incidencia sin estudiante asociado');
            return;
        }
        
        $estudiante = $incidencia->estudiante;
        $docente = $incidencia->docente;

        if (!$docente) {
            Log::warning('Incidencia sin docente asociado');
            return;
        }

        // Contar faltas en los últimos 15 días
        $totalFaltas = Incidencia::where('estudiante_id', $estudiante->id)
            ->where('created_at', '>=', Carbon::now()->subDays(15))
            ->count();

        Log::info('📊 Total faltas: ' . $totalFaltas);

        if ($totalFaltas >= 3) {
            Log::info('✅ Generando citación...');
            $citacion = Citacion::create([
                'estudiante_id' => $estudiante->id,
                'docente_id' => $docente->id,
                'fecha_programada' => Carbon::now()->addDays(3),
                'motivo' => 'Acumulación de 3 faltas en 15 días',
                'estado' => 'pendiente'
            ]);

            Log::info('📋 Citación creada ID: ' . $citacion->id);
            
            // Verificar que el estudiante tiene padre antes de encolar
            if ($estudiante->padre && $estudiante->padre->telefono) {
                EnviarNotificacionMetaWhatsApp::dispatch($citacion);
                Log::info('📱 Job de WhatsApp encolado para ' . $estudiante->padre->telefono);
            } else {
                Log::warning('⚠️ No se puede enviar WhatsApp: Padre sin teléfono');
            }
        } else {
            Log::info('⚠️ No se alcanzaron las 3 faltas (faltas: ' . $totalFaltas . ')');
        }
    }
}