<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function whatsapp($id)
    {
        $estudiante = Estudiante::with(['incidencias', 'curso', 'padre'])->findOrFail($id);
        
        // Construir mensaje para WhatsApp
        $mensaje = "📋 *KARDEX CONDUCTUAL*%0A";
        $mensaje .= "UNIDAD EDUCATIVA GRAL. JOSE DE SAN MARTIN A%0A%0A";
        $mensaje .= "👤 *Estudiante:* {$estudiante->nombre} {$estudiante->apellido}%0A";
        $mensaje .= "📚 *Curso:* {$estudiante->curso->nombre} {$estudiante->curso->paralelo}%0A";
        $mensaje .= "👨‍👩‍👦 *Tutor:* " . ($estudiante->padre ? $estudiante->padre->nombre . ' ' . $estudiante->padre->apellido : 'No registrado') . "%0A";
        $mensaje .= "📞 *Teléfono:* " . ($estudiante->padre ? $estudiante->padre->telefono : 'N/A') . "%0A%0A";
        $mensaje .= "*📋 HISTORIAL DE FALTAS:*%0A";
        $mensaje .= "┌─────────────┬──────────────────┬────────────────────────┐%0A";
        $mensaje .= "│ FECHA       │ MATERIA          │ OBSERVACIÓN            │%0A";
        $mensaje .= "├─────────────┼──────────────────┼────────────────────────┤%0A";
        
        foreach ($estudiante->incidencias as $inc) {
            $fecha = $inc->created_at->format('d/m/Y');
            $materia = $inc->materia ?? 'N/A';
            $obs = $inc->tipo . ($inc->descripcion ? ': ' . $inc->descripcion : '');
            $mensaje .= "│ $fecha │ $materia │ $obs │%0A";
        }
        
        $mensaje .= "└─────────────┴──────────────────┴────────────────────────┘%0A%0A";
        $mensaje .= "*Total de faltas:* {$estudiante->incidencias->count()}%0A";
        $mensaje .= "*Generado:* " . now()->format('d/m/Y H:i');
        
        // Número de teléfono del padre (o del profesor para pruebas)
        $telefono = $estudiante->padre ? $estudiante->padre->telefono : '59170000000'; // Cambia por tu número
        
        // Limpiar número (solo dígitos)
        $telefono = preg_replace('/[^0-9]/', '', $telefono);
        if (substr($telefono, 0, 1) !== '5') {
            $telefono = '591' . $telefono;
        }
        
        $url = "https://api.whatsapp.com/send?phone={$telefono}&text={$mensaje}";
        
        return redirect()->away($url);
    }

    public function generarPDF($id)
{
    $estudiante = Estudiante::with(['incidencias', 'curso', 'padre'])->findOrFail($id);
    
    $data = [
        'estudiante' => $estudiante,
        'tiposFalta' => [
            'Atraso' => 1,
            'Falto a clases' => 2,
            'Falta con licencia' => 3,
            'Sin uniforme' => 4,
            'Indisciplina' => 5,
            'No hizo tarea' => 6,
            'No dio examen' => 7,
            'No trabaja en clases' => 8,
            'Tiene citación' => 9,
            'Tarea incompleta' => 10,
        ],
        'fecha' => now()->format('d/m/Y H:i')
    ];
    
    $pdf = Pdf::loadView('pdf.kardex', $data);
    return $pdf->download('kardex_' . $estudiante->nombre . '_' . $estudiante->apellido . '.pdf');
}
}