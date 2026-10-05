<?php

namespace App\Http\Controllers;

use Twilio\Rest\Client;
use Twilio\Http\CurlClient;

class PruebaWhatsAppController extends Controller
{
    public function enviar()
    {
        // Número de WhatsApp personal (el que usaste para unirte al Sandbox)
        $telefono = '591' . '70000000'; // Cambia por TU número (ej: 59170000000)
        
        $mensaje = "📢 *PRUEBA DE WHATSAPP - UNIDAD EDUCATIVA SAN MARTÍN*\n\n";
        $mensaje .= "Este es un mensaje de prueba para verificar que la integración con Twilio funciona correctamente.\n\n";
        $mensaje .= "✅ Conexión exitosa!";

        try {
            // Crear cliente HTTP con verificación SSL deshabilitada (SOLO PARA PRUEBAS)
            $httpClient = new CurlClient([
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ]);

            // Crear cliente de Twilio con el cliente HTTP personalizado
            $twilio = new Client(
                env('TWILIO_SID'),
                env('TWILIO_AUTH_TOKEN'),
                null,
                null,
                $httpClient // 👈 Quinto argumento: cliente HTTP
            );

            $twilio->messages->create(
                "whatsapp:{$telefono}",
                [
                    'from' => 'whatsapp:' . env('TWILIO_WHATSAPP_NUMBER'),
                    'body' => $mensaje
                ]
            );

            return "✅ Mensaje enviado correctamente a {$telefono}";

        } catch (\Exception $e) {
            return "❌ Error: " . $e->getMessage();
        }
    }
}