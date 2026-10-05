<?php

namespace App\Jobs;

use App\Models\Citacion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

        $telefono = preg_replace('/[^0-9]/', '', $padre->telefono);
        if (substr($telefono, 0, 1) !== '5') {
            $telefono = '591' . $telefono;
        }

        $fecha = Carbon::parse($this->citacion->fecha_programada)->format('d/m/Y');
        $hora = Carbon::parse($this->citacion->fecha_programada)->format('h:i A');

        try {
            $url = "https://graph.facebook.com/" . env('META_WHATSAPP_API_VERSION') . "/" . env('META_WHATSAPP_PHONE_ID') . "/messages";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('META_WHATSAPP_TOKEN'),
                'Content-Type' => 'application/json',
            ])->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $telefono,
                'type' => 'template',
                'template' => [
                    'name' => 'aviso_faltas', // debe coincidir EXACTO con el nombre que le pongas en Meta
                    'language' => ['code' => 'es'],
                    'components' => [
                        [
                            'type' => 'body',
                            'parameters' => [
                                ['type' => 'text', 'text' => "{$estudiante->nombre} {$estudiante->apellido}"],
                                ['type' => 'text', 'text' => "{$curso->nombre} {$curso->paralelo}"],
                                ['type' => 'text', 'text' => $fecha],
                                ['type' => 'text', 'text' => $hora],
                                ['type' => 'text', 'text' => $this->citacion->motivo],
                            ],
                        ],
                    ],
                ],
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