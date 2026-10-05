<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kardex de {{ $estudiante->nombre }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { font-size: 16px; margin: 0; }
        .header h2 { font-size: 14px; margin: 2px 0; }
        .data { margin: 10px 0; }
        .data p { margin: 3px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #e5e7eb; border: 1px solid #000; padding: 5px; text-align: left; }
        td { border: 1px solid #000; padding: 5px; }
        .footer { margin-top: 20px; font-size: 10px; text-align: center; color: #666; }
        .codigos { margin-top: 10px; font-size: 10px; }
        .codigos span { display: inline-block; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>UNIDAD EDUCATIVA "GRAL JOSE DE SAN MARTIN A"</h1>
        <h2>EDUCACIÓN SECUNDARIA COMUNITARIA PRODUCTIVA</h2>
        <h2>REGISTRO DE SEGUIMIENTO ACADÉMICO - {{ date('Y') }}</h2>
    </div>

    <div class="data">
        <p><strong>ESTUDIANTE:</strong> {{ $estudiante->nombre }} {{ $estudiante->apellido }}</p>
        <p><strong>CURSO:</strong> {{ $estudiante->curso ? $estudiante->curso->nombre . ' ' . $estudiante->curso->paralelo : 'N/A' }}</p>
        <p><strong>PADRE/TUTOR:</strong> {{ $estudiante->padre ? $estudiante->padre->nombre . ' ' . $estudiante->padre->apellido : 'No registrado' }}</p>
        <p><strong>TELÉFONO:</strong> {{ $estudiante->padre ? $estudiante->padre->telefono : 'N/A' }}</p>
        <p><strong>DOMICILIO:</strong> {{ $estudiante->direccion ?? 'No registrado' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:20%">FECHA</th>
                <th style="width:25%">ÁREA / MAESTRA</th>
                <th style="width:55%">OBSERVACIONES</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estudiante->incidencias as $incidencia)
            <tr>
                <td>{{ $incidencia->created_at->format('d/m/Y') }}</td>
                <td>{{ $incidencia->materia ?? 'N/A' }}</td>
                <td>
                    @php $codigo = $tiposFalta[$incidencia->tipo] ?? '?'; @endphp
                    <strong>{{ $codigo }})</strong> {{ $incidencia->tipo }}
                    @if($incidencia->descripcion)
                        <br><small>{{ $incidencia->descripcion }}</small>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align:center;">No hay incidencias registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="codigos">
        <p><strong>CÓDIGOS DE FALTA:</strong></p>
        @foreach($tiposFalta as $nombre => $codigo)
            <span><strong>{{ $codigo }})</strong> {{ $nombre }}</span>
        @endforeach
    </div>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>