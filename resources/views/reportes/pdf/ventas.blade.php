<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #10b981; }
        .header h1 { color: #059669; font-size: 20pt; margin-bottom: 5px; }
        .header p { color: #64748b; font-size: 9pt; }
        .info-section { background-color: #f8fafc; padding: 12px; border-radius: 6px; margin-bottom: 18px; font-size: 9pt; }
        .info-row { margin-bottom: 6px; }
        .info-label { font-weight: bold; color: #475569; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 20px; }
        .stat-card { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px; border-radius: 6px; text-align: center; }
        .stat-card.blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .stat-card.purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
        .stat-card.orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .stat-label { font-size: 8pt; opacity: 0.9; margin-bottom: 4px; }
        .stat-value { font-size: 16pt; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 9pt; }
        thead { background-color: #059669; color: white; }
        th { padding: 8px 6px; text-align: left; font-size: 8pt; font-weight: bold; text-transform: uppercase; }
        tbody tr { border-bottom: 1px solid #e2e8f0; }
        tbody tr:nth-child(even) { background-color: #f8fafc; }
        td { padding: 6px; color: #334155; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 10px; font-size: 7pt; font-weight: bold; }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-info { background-color: #dbeafe; color: #1e40af; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .footer { margin-top: 25px; padding-top: 12px; border-top: 2px solid #e2e8f0; text-align: center; font-size: 7pt; color: #94a3b8; }
        .no-data { text-align: center; padding: 30px; color: #94a3b8; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE VENTAS</h1>
        <p>Análisis detallado de ventas realizadas</p>
        <p style="margin-top: 4px;">Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Período:</span>
            <span>{{ $datos['fecha_inicio'] ?? 'N/A' }} - {{ $datos['fecha_fin'] ?? date('d/m/Y') }}</span>
        </div>
        @if(isset($datos['cliente']))
        <div class="info-row">
            <span class="info-label">Cliente:</span>
            <span>{{ $datos['cliente'] }}</span>
        </div>
        @endif
    </div>

    @if(isset($datos['estadisticas']))
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Ventas</div>
            <div class="stat-value">{{ number_format($datos['estadisticas']['total_ventas'] ?? 0) }}</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-label">Total Ingresos</div>
            <div class="stat-value">${{ number_format($datos['estadisticas']['total_monto'] ?? 0, 2) }}</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-label">Productos Vendidos</div>
            <div class="stat-value">{{ number_format($datos['estadisticas']['productos_vendidos'] ?? 0) }}</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-label">Ticket Promedio</div>
            <div class="stat-value">${{ number_format($datos['estadisticas']['ticket_promedio'] ?? 0, 2) }}</div>
        </div>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>N° Venta</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th class="text-center">Items</th>
                <th class="text-right">Total</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($datos['ventas'] ?? [] as $venta)
            <tr>
                <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                <td><strong>{{ $venta->numero_venta }}</strong></td>
                <td>{{ $venta->cliente->nombre_completo ?? 'N/A' }}</td>
                <td>{{ $venta->vendedor->name ?? 'N/A' }}</td>
                <td class="text-center">{{ $venta->detalles_count ?? 0 }}</td>
                <td class="text-right"><strong>${{ number_format($venta->total, 2) }}</strong></td>
                <td class="text-center">
                    @if($venta->estado === 'completada')
                        <span class="badge badge-success">COMPLETADA</span>
                    @elseif($venta->estado === 'anulada')
                        <span class="badge badge-warning">ANULADA</span>
                    @else
                        <span class="badge badge-info">{{ strtoupper($venta->estado) }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="no-data">No se encontraron ventas</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema de Gestión de Ventas | {{ config('app.name') }}</p>
    </div>
</body>
</html>
