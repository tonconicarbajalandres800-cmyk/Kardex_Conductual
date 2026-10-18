<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Estudiantes</title>
    <style>
        @page {
            margin: 40px 40px 60px 40px; /* Márgenes del documento */
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        /* Cabecera Formal */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1e3a8a;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header-table td {
            border: none; /* Quitamos bordes a esta tabla */
            padding: 0;
        }
        .logo {
            width: 80px;
            height: 80px;
        }
        .header-text {
            text-align: center;
        }
        .header-text h1 {
            margin: 0;
            font-size: 16px;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .header-text h2 {
            margin: 5px 0 0;
            font-size: 14px;
            color: #333;
            font-weight: normal;
        }
        .header-text p {
            margin: 3px 0 0;
            font-size: 12px;
            color: #555;
        }
        
        /* Información del Curso */
        .info-curso {
            margin-bottom: 15px;
            font-size: 13px;
            background-color: #f8fafc;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
        }
        .info-curso strong {
            color: #1e3a8a;
        }

        /* Tabla de Estudiantes */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #94a3b8;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 11px;
            text-transform: uppercase;
            text-align: center;
        }
        td {
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f1f5f9;
        }
        
        /* Firmas */
        .firmas {
            width: 100%;
            margin-top: 60px;
            text-align: center;
        }
        .firmas td {
            border: none;
            width: 50%;
        }
        .linea-firma {
            width: 200px;
            border-top: 1px solid #333;
            margin: 0 auto;
            margin-top: 40px;
        }
        .firmas p {
            margin: 5px 0 0;
            font-size: 11px;
            color: #333;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <!-- CABECERA CON ESCUDO -->
    <table class="header-table">
        <tr>
            <td width="15%" align="left">
                <!-- Asegúrate de que la ruta del escudo sea correcta -->
                <img src="{{ public_path('images/escudo.jpg') }}" alt="Escudo" class="logo">
            </td>
            <td width="85%" class="header-text">
                <h1>U.E. GRAL. JOSÉ DE SAN MARTÍN</h1>
                <h2>LISTA OFICIAL DE ESTUDIANTES</h2>
                <p>Gestión Educativa 2026</p>
            </td>
        </tr>
    </table>

    <!-- INFORMACIÓN DEL CURSO -->
    <div class="info-curso">
        <table style="width: 100%; border: none; margin: 0;">
            <tr>
                <td style="border: none; padding: 0;"><strong>CURSO:</strong> {{ $curso->nombre }} {{ $curso->paralelo }}</td>
                <td style="border: none; padding: 0;"><strong>GESTIÓN:</strong> {{ $curso->gestion }}</td>
                <td style="border: none; padding: 0; text-align: right;"><strong>TOTAL:</strong> {{ $estudiantes->count() }} Estudiantes</td>
            </tr>
        </table>
    </div>

    <!-- TABLA DE ESTUDIANTES -->
    <table>
        <thead>
            <tr>
                <th width="5%">Nº</th>
                <th width="15%">C.I.</th>
                <th width="40%">Apellidos y Nombres</th>
                <th width="20%">Fecha Nac.</th>
                <th width="20%">Nº Lista</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estudiantes as $index => $est)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td align="center">{{ $est->ci }}</td>
                    <td>{{ $est->apellido }} {{ $est->nombre }}</td>
                    <td align="center">{{ $est->fecha_nacimiento ? \Carbon\Carbon::parse($est->fecha_nacimiento)->format('d/m/Y') : '---' }}</td>
                    <td align="center">{{ $est->numero_lista ?? '---' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" align="center">No hay estudiantes registrados en este curso.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- FIRMAS -->
    <table class="firmas">
        <tr>
            <td>
                <div class="linea-firma"></div>
                <p>Firma del Docente</p>
            </td>
            <td>
                <div class="linea-firma"></div>
                <p>Dirección / Regencia</p>
            </td>
        </tr>
    </table>

    <!-- PIE DE PÁGINA -->
    <div class="footer">
        Documento generado el {{ date('d/m/Y') }} a las {{ date('H:i') }} por el sistema de Kardex Conductual.
    </div>

</body>
</html>