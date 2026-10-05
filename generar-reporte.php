<?php
// Ejecutar las pruebas y guardar la salida
echo "Ejecutando pruebas...\n";
$output = shell_exec('php artisan test --testsuite=Unit --no-ansi 2>&1');

// Extraer estadísticas
$lines = explode("\n", $output);
$passed = 0;
$failed = 0;
$total = 0;
$testNames = [];

foreach ($lines as $line) {
    if (strpos($line, '✓') !== false) {
        $passed++;
        $total++;
        $testNames[] = ['name' => trim(substr($line, strpos($line, '✓') + 2)), 'status' => 'passed'];
    } elseif (strpos($line, '⨯') !== false) {
        $failed++;
        $total++;
        $testNames[] = ['name' => trim(substr($line, strpos($line, '⨯') + 2)), 'status' => 'failed'];
    }
}

$successRate = $total > 0 ? round(($passed / $total) * 100, 2) : 0;

$html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pruebas Unitarias</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 35px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border-left: 4px solid #667eea;
        }
        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
        }
        .stat-card .label {
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 5px;
        }
        .stat-card.passed { border-left-color: #27ae60; }
        .stat-card.failed { border-left-color: #e74c3c; }
        .stat-card.total { border-left-color: #3498db; }
        .stat-card.rate { border-left-color: #f39c12; }
        .stat-card .number.pass { color: #27ae60; }
        .stat-card .number.fail { color: #e74c3c; }
        .stat-card .number.total { color: #3498db; }
        .stat-card .number.rate { color: #f39c12; }
        .progress-bar {
            width: 100%;
            height: 30px;
            background: #ecf0f1;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 30px;
        }
        .progress-bar .fill {
            height: 100%;
            background: linear-gradient(90deg, #27ae60, #2ecc71);
            text-align: center;
            line-height: 30px;
            color: white;
            font-weight: bold;
            transition: width 0.3s;
        }
        .test-item {
            padding: 12px 18px;
            margin-bottom: 8px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .test-item.passed {
            background: #d5f4e6;
            border-left: 4px solid #27ae60;
        }
        .test-item.failed {
            background: #fde8e8;
            border-left: 4px solid #e74c3c;
        }
        .test-name {
            font-weight: 500;
            color: #2c3e50;
        }
        .test-status {
            font-weight: bold;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 13px;
        }
        .test-status.pass {
            background: #27ae60;
            color: white;
        }
        .test-status.fail {
            background: #e74c3c;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            color: #7f8c8d;
            font-size: 14px;
        }
        .timestamp {
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Reporte de Pruebas Unitarias</h1>
        <div class="header">
            <span><strong>Proyecto:</strong> Kardex Conductual</span>
            <span class="timestamp"><strong>Fecha:</strong> <?php echo date('d/m/Y H:i:s'); ?></span>
        </div>

        <div class="stats">
            <div class="stat-card total">
                <div class="number total">$total</div>
                <div class="label">Total Pruebas</div>
            </div>
            <div class="stat-card passed">
                <div class="number pass">$passed</div>
                <div class="label">✅ Pasadas</div>
            </div>
            <div class="stat-card failed">
                <div class="number fail">$failed</div>
                <div class="label">❌ Fallidas</div>
            </div>
            <div class="stat-card rate">
                <div class="number rate">{$successRate}%</div>
                <div class="label">Tasa de Éxito</div>
            </div>
        </div>

        <div class="progress-bar">
            <div class="fill" style="width: {$successRate}%;">{$successRate}%</div>
        </div>

        <h2>📋 Detalle de Pruebas</h2>
HTML;

foreach ($testNames as $test) {
    $statusClass = $test['status'];
    $statusText = $test['status'] === 'passed' ? '✅ PASÓ' : '❌ FALLÓ';
    $html .= <<<HTML
        <div class="test-item {$statusClass}">
            <span class="test-name">{$test['name']}</span>
            <span class="test-status {$statusClass}">{$statusText}</span>
        </div>
HTML;
}

$html .= <<<HTML
        <div class="footer">
            <p>Reporte generado automáticamente por PHPUnit</p>
            <p>Sistema Kardex Conductual - U.E. Gral. José de San Martín</p>
        </div>
    </div>
</body>
</html>
HTML;

file_put_contents('reporte-personalizado.html', $html);
echo "✅ Reporte personalizado generado: reporte-personalizado.html\n";
echo "📊 Abre el archivo en tu navegador.\n";
?>