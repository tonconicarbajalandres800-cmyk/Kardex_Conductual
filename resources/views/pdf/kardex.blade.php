<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kardex - {{ $estudiante->nombre }} {{ $estudiante->apellido }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Arial', sans-serif; 
            padding: 20px;
            background: #fff;
        }
        
        /* ========================================== */
        /* ENCABEZADO CON ESCUDO Y FOTO CUADRADA      */
        /* ========================================== */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px double #1a3a5c;
            padding-bottom: 15px;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .header-left {
            width: 80px;
            height: 80px;
            flex-shrink: 0;
        }
        .header-left img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border: 2px solid #1a3a5c;
        }
        .header-center {
            flex: 1;
            text-align: center;
            padding: 0 10px;
        }
        .header-center h1 {
            font-size: 18px;
            color: #1a3a5c;
            margin: 0;
            letter-spacing: 1px;
        }
        .header-center p {
            font-size: 11px;
            color: #333;
            margin: 2px 0;
        }
        .header-center .subtitulo {
            font-size: 14px;
            font-weight: bold;
            color: #1a3a5c;
            margin-top: 5px;
            background: #e8edf3;
            padding: 3px 15px;
            display: inline-block;
        }
        .header-right {
            width: 80px;
            height: 80px;
            flex-shrink: 0;
            border: 2px solid #1a3a5c;
            overflow: hidden;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header-right img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .header-right .sin-foto {
            font-size: 32px;
            font-weight: bold;
            color: #1a3a5c;
        }

        /* ========================================== */
        /* DATOS DEL ESTUDIANTE                       */
        /* ========================================== */
        .info {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .info td {
            padding: 6px 10px;
            font-size: 12px;
            border-bottom: 1px dashed #ccc;
        }
        .info .label {
            font-weight: bold;
            color: #1a3a5c;
            width: 120px;
        }

        /* ========================================== */
        /* TABLA DE INCIDENCIAS                       */
        /* ========================================== */
        .titulo-tabla {
            font-size: 14px;
            font-weight: bold;
            color: #1a3a5c;
            margin: 15px 0 8px 0;
            padding: 5px 10px;
            background: #e8edf3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 5px;
        }
        th {
            background: #1a3a5c;
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 7px 10px;
            border: 1px solid #ccc;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }

        /* ========================================== */
        /* LEYENDA DE CODIGOS                         */
        /* ========================================== */
        .leyenda {
            margin-top: 15px;
            padding: 10px 15px;
            background: #f5f7fa;
            font-size: 10px;
        }
        .leyenda strong {
            color: #1a3a5c;
        }
        .leyenda .codigo {
            display: inline-block;
            background: #fff;
            padding: 2px 8px;
            margin: 2px 3px;
            border: 1px solid #ddd;
        }

        /* ========================================== */
        /* PIE DE PAGINA                              */
        /* ========================================== */
        .footer {
            margin-top: 20px;
            font-size: 9px;
            text-align: right;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <!-- ========================================== -->
    <!-- ENCABEZADO CON ESCUDO Y FOTO CUADRADA      -->
    <!-- ========================================== -->
    <div class="header">
        <div class="header-top">
            <!-- ESCUDO (izquierda) -->
            <div class="header-left">
                <img src="{{ public_path('images/escudo.jpg') }}" alt="Escudo">
            </div>

            <!-- TITULOS (centro) -->
            <div class="header-center">
                <h1>UNIDAD EDUCATIVA "GRAL JOSE DE SAN MARTIN A"</h1>
                <p>EDUCACION SECUNDARIA COMUNITARIA PRODUCTIVA</p>
                <div class="subtitulo">REGISTRO DE SEGUIMIENTO ACADEMICO - {{ date('Y') }}</div>
            </div>

            <!-- FOTO DEL ESTUDIANTE (derecha) - CUADRADA -->
            <div class="header-right">
                @php
                    $fotoUrl = null;
                    $idEstudiante = $estudiante->id ?? 0;
                    $nombreEstudiante = $estudiante->nombre ?? '';

                    // Buscar la foto en la carpeta correcta para PDF
                    $rutasPosibles = [
                        public_path('images/storage/fotos/' . $idEstudiante . '.jpg'),
                        public_path('images/storage/fotos/' . $idEstudiante . '.png'),
                        public_path('storage/fotos/' . $idEstudiante . '.jpg'),
                        public_path('storage/fotos/' . $idEstudiante . '.png'),
                        public_path('fotos/' . $idEstudiante . '.jpg'),
                    ];

                    foreach ($rutasPosibles as $ruta) {
                        if (file_exists($ruta)) {
                            $fotoUrl = $ruta;
                            break;
                        }
                    }

                    // Si no se encuentra por ID, buscar en la relacion fotos
                    if (!$fotoUrl && isset($estudiante->fotos) && $estudiante->fotos->count() > 0) {
                        $ultimaFoto = $estudiante->fotos->last();
                        $nombreArchivo = basename($ultimaFoto->ruta);
                        $rutasRelacion = [
                            public_path('images/storage/fotos/' . $nombreArchivo),
                            public_path('storage/fotos/' . $nombreArchivo),
                            public_path('fotos/' . $nombreArchivo),
                        ];
                        foreach ($rutasRelacion as $ruta) {
                            if (file_exists($ruta)) {
                                $fotoUrl = $ruta;
                                break;
                            }
                        }
                    }
                @endphp

                @if($fotoUrl && file_exists($fotoUrl))
                    <img src="{{ $fotoUrl }}" alt="Foto de {{ $nombreEstudiante }}">
                @else
                    <span class="sin-foto">{{ strtoupper(substr($nombreEstudiante, 0, 1)) }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- DATOS DEL ESTUDIANTE                       -->
    <!-- ========================================== -->
    <table class="info">
        <tr>
            <td class="label">ESTUDIANTE:</td>
            <td><strong>{{ $estudiante->nombre }} {{ $estudiante->apellido }}</strong></td>
            <td class="label">CURSO:</td>
            <td><strong>{{ $estudiante->curso ? $estudiante->curso->nombre . ' ' . $estudiante->curso->paralelo : 'N/A' }}</strong></td>
        </tr>
        <tr>
            <td class="label">PADRE/TUTOR:</td>
            <td><strong>{{ $estudiante->padre ? $estudiante->padre->nombre . ' ' . $estudiante->padre->apellido : 'No registrado' }}</strong></td>
            <td class="label">TELEFONO:</td>
            <td><strong>{{ $estudiante->padre ? $estudiante->padre->telefono : 'N/A' }}</strong></td>
        </tr>
        <tr>
            <td class="label">DOMICILIO:</td>
            <td colspan="3"><strong>{{ $estudiante->direccion ?? 'No registrado' }}</strong></td>
        </tr>
    </table>

    <!-- ========================================== -->
    <!-- TABLA DE INCIDENCIAS                       -->
    <!-- ========================================== -->
    <div class="titulo-tabla">HISTORIAL DE INCIDENCIAS</div>
    <table>
        <thead>
            <tr>
                <th style="width:15%">FECHA</th>
                <th style="width:25%">AREA / MAESTRA</th>
                <th style="width:60%">OBSERVACIONES</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estudiante->incidencias as $inc)
            <tr>
                <td>{{ $inc->created_at->format('d/m/Y') }}</td>
                <td>{{ $inc->materia ?? 'N/A' }}</td>
                <td>
                    @php $codigo = $tiposFalta[$inc->tipo] ?? '?'; @endphp
                    <strong>{{ $codigo }})</strong> {{ $inc->tipo }}
                    @if($inc->descripcion) <br><small style="color:#666;">{{ $inc->descripcion }}</small> @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align:center; padding:20px; color:#999;">
                    No hay incidencias registradas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ========================================== -->
    <!-- LEYENDA DE CODIGOS                         -->
    <!-- ========================================== -->
    <div class="leyenda">
        <strong>CODIGOS DE FALTA:</strong><br>
        @foreach($tiposFalta as $nombre => $codigo)
            <span class="codigo"><strong>{{ $codigo }})</strong> {{ $nombre }}</span>
        @endforeach
    </div>

    <!-- ========================================== -->
    <!-- PIE DE PAGINA                              -->
    <!-- ========================================== -->
    <div class="footer">
        Generado el {{ $fecha ?? now()->format('d/m/Y H:i') }} | Sistema Kardex Conductual
    </div>

</body>
</html>