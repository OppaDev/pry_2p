<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Inventario</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #3b82f6; }
        .header h1 { color: #2563eb; font-size: 20pt; margin-bottom: 5px; }
        .header p { color: #64748b; font-size: 9pt; }
        .info-section { background-color: #f8fafc; padding: 12px; border-radius: 6px; margin-bottom: 18px; font-size: 9pt; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 20px; }
        .stat-card { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 12px; border-radius: 6px; text-align: center; }
        .stat-card.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .stat-card.red { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .stat-card.purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
        .stat-label { font-size: 8pt; opacity: 0.9; margin-bottom: 4px; }
        .stat-value { font-size: 16pt; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; font-size: 9pt; }
        thead { background-color: #2563eb; color: white; }
        th { padding: 8px 6px; text-align: left; font-size: 8pt; font-weight: bold; text-transform: uppercase; }
        tbody tr { border-bottom: 1px solid #e2e8f0; }
        tbody tr:nth-child(even) { background-color: #f8fafc; }
        td { padding: 6px; color: #334155; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 10px; font-size: 7pt; font-weight: bold; }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .footer { margin-top: 25px; padding-top: 12px; border-top: 2px solid #e2e8f0; text-align: center; font-size: 7pt; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE INVENTARIO</h1>
        <p>Estado actual del inventario</p>
        <p style="margin-top: 4px;">Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <strong>Filtros aplicados:</strong>
        Categoría: {{ $datos['categoria'] ?? 'Todas' }} |
        Estado: {{ $datos['estado'] ?? 'Todos' }}
    </div>

    @if(isset($datos['estadisticas']))
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Productos</div>
            <div class="stat-value">{{ number_format($datos['estadisticas']['total_productos'] ?? 0) }}</div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Valor Total</div>
            <div class="stat-value">${{ number_format($datos['estadisticas']['valor_total'] ?? 0, 2) }}</div>
        </div>
        <div class="stat-card red">
            <div class="stat-label">Bajo Stock</div>
            <div class="stat-value">{{ number_format($datos['estadisticas']['productos_bajo_stock'] ?? 0) }}</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-label">Stock Total</div>
            <div class="stat-value">{{ number_format($datos['estadisticas']['unidades_totales'] ?? 0) }}</div>
        </div>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th class="text-center">Stock</th>
                <th class="text-right">Precio</th>
                <th class="text-right">Valor</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($datos['productos'] ?? [] as $producto)
            <tr>
                <td><strong>{{ $producto->codigo }}</strong></td>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->categoria->nombre ?? 'N/A' }}</td>
                <td class="text-center">{{ number_format($producto->stock_actual) }}</td>
                <td class="text-right">${{ number_format($producto->precio, 2) }}</td>
                <td class="text-right">${{ number_format($producto->stock_actual * $producto->precio, 2) }}</td>
                <td class="text-center">
                    @if($producto->stock_actual <= $producto->stock_minimo)
                        <span class="badge badge-danger">BAJO</span>
                    @elseif($producto->stock_actual <= ($producto->stock_minimo * 1.5))
                        <span class="badge badge-warning">MEDIO</span>
                    @else
                        <span class="badge badge-success">ÓPTIMO</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">No se encontraron productos</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema de Gestión de Ventas | {{ config('app.name') }}</p>
    </div>
</body>
</html>
