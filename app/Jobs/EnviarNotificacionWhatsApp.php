<?php

namespace App\Jobs;

use App\Models\Citacion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EnviarNotificacionMetaWhatsApp implements ShouldQueue
{
    use Queueable;

    protected $citacion;

    public function __construct(Citacion $citacion)
    {
        $this->citacion = $citacion;
    }

    public function handle()
    {
        $estudiante = $this->citacion->estudiante;
        $padre = $estudiante->padre;
        $curso = $estudiante->curso;

        if (!$padre || !$padre->telefono) {
            Log::warning('No se pudo enviar WhatsApp: Padre sin teléfono');
            return;
        }

        // Limpiar número (solo dígitos)
        $telefono = preg_replace('/[^0-9]/', '', $padre->telefono);
        if (substr($telefono, 0, 1) !== '5') {
            $telefono = '591' . $telefono;
        }

        // Formatear fecha
        $fecha = \Carbon\Carbon::parse($this->citacion->fecha_programada)->format('d/m/Y');
        $hora = \Carbon\Carbon::parse($this->citacion->fecha_programada)->format('h:i A');

        // Construir mensaje
        $mensaje = "📢 *UNIDAD EDUCATIVA SAN MARTÍN*\n\n";
        $mensaje .= "Estimado padre de familia,\n";
        $mensaje .= "Su hijo(a) *{$estudiante->nombre} {$estudiante->apellido}* del curso *{$curso->nombre} {$curso->paralelo}* ha acumulado 3 faltas conductuales en los últimos 15 días.\n\n";
        $mensaje .= "📅 *Reunión programada:* {$fecha} a las {$hora}\n";
        $mensaje .= "📍 *Lugar:* Dirección de la Unidad Educativa\n\n";
        $mensaje .= "Por favor, confirme su asistencia en la plataforma.\n";
        $mensaje .= "📌 *Motivo:* " . $this->citacion->motivo;

        try {
            $url = "https://graph.facebook.com/" . env('META_WHATSAPP_API_VERSION') . "/" . env('META_WHATSAPP_PHONE_ID') . "/messages";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('META_WHATSAPP_TOKEN'),
                'Content-Type' => 'application/json',
            ])->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $telefono,
                'type' => 'text',
                'text' => [
                    'body' => $mensaje
                ]
            ]);

            if ($response->successful()) {
                Log::info('✅ WhatsApp enviado correctamente a ' . $telefono);
            } else {
                Log::error('❌ Error enviando WhatsApp: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('❌ Error enviando WhatsApp: ' . $e->getMessage());
        }
    }
}