<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos Más Vendidos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #8b5cf6; }
        .header h1 { color: #7c3aed; font-size: 20pt; margin-bottom: 5px; }
        .header p { color: #64748b; font-size: 9pt; }
        .info-section { background-color: #f8fafc; padding: 12px; border-radius: 6px; margin-bottom: 18px; font-size: 9pt; }
        table { width: 100%; border-collapse: collapse; font-size: 9pt; }
        thead { background-color: #7c3aed; color: white; }
        th { padding: 8px 6px; text-align: left; font-size: 8pt; font-weight: bold; text-transform: uppercase; }
        tbody tr { border-bottom: 1px solid #e2e8f0; }
        tbody tr:nth-child(even) { background-color: #f8fafc; }
        td { padding: 6px; color: #334155; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .ranking { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: white; padding: 4px 8px; border-radius: 50%; font-weight: bold; font-size: 8pt; }
        .footer { margin-top: 25px; padding-top: 12px; border-top: 2px solid #e2e8f0; text-align: center; font-size: 7pt; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PRODUCTOS MÁS VENDIDOS</h1>
        <p>Ranking de productos por volumen de ventas</p>
        <p style="margin-top: 4px;">Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <strong>Período:</strong> {{ $datos['fecha_inicio'] ?? 'N/A' }} - {{ $datos['fecha_fin'] ?? date('d/m/Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Código</th>
                <th>Producto</th>
                <th class="text-center">Cantidad Vendida</th>
                <th class="text-right">Ingresos</th>
                <th class="text-center">% Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($datos['productos'] ?? [] as $index => $producto)
            <tr>
                <td class="text-center"><span class="ranking">{{ $index + 1 }}</span></td>
                <td><strong>{{ $producto->codigo }}</strong></td>
                <td>{{ $producto->nombre }}</td>
                <td class="text-center">{{ number_format($producto->total_vendido ?? 0) }}</td>
                <td class="text-right">${{ number_format($producto->ingresos_generados ?? 0, 2) }}</td>
                <td class="text-center">{{ number_format($producto->porcentaje ?? 0, 1) }}%</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align: center; padding: 30px; color: #94a3b8;">No hay datos disponibles</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema de Gestión de Ventas | {{ config('app.name') }}</p>
    </div>
</body>
</html>
