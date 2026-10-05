<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FotoHelper
{
    public static function getFotoEstudiante($estudianteId)
    {
        // Verificar en diferentes ubicaciones
        $rutas = [
            // Ruta 1: Public
            public_path('images/storage/fotos/' . $estudianteId . '.jpg'),
            public_path('images/storage/fotos/' . $estudianteId . '.png'),
            public_path('storage/fotos/' . $estudianteId . '.jpg'),
            public_path('storage/fotos/' . $estudianteId . '.png'),
            public_path('fotos/' . $estudianteId . '.jpg'),
            public_path('fotos/' . $estudianteId . '.png'),
            
            // Ruta 2: Storage
            storage_path('app/public/fotos/' . $estudianteId . '.jpg'),
            storage_path('app/public/fotos/' . $estudianteId . '.png'),
        ];
        
        foreach ($rutas as $ruta) {
            if (file_exists($ruta)) {
                // Si la ruta es en public
                if (strpos($ruta, public_path()) === 0) {
                    return asset(str_replace(public_path(), '', $ruta));
                }
                // Si es en storage
                return Storage::url('fotos/' . $estudianteId . '.jpg');
            }
        }
        
        return null;
    }
}