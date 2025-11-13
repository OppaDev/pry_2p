<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Movimientos de Inventario</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #06b6d4; }
        .header h1 { color: #0891b2; font-size: 20pt; margin-bottom: 5px; }
        .header p { color: #64748b; font-size: 9pt; }
        .info-section { background-color: #f8fafc; padding: 12px; border-radius: 6px; margin-bottom: 18px; font-size: 9pt; }
        table { width: 100%; border-collapse: collapse; font-size: 8pt; }
        thead { background-color: #0891b2; color: white; }
        th { padding: 8px 6px; text-align: left; font-size: 7pt; font-weight: bold; text-transform: uppercase; }
        tbody tr { border-bottom: 1px solid #e2e8f0; }
        tbody tr:nth-child(even) { background-color: #f8fafc; }
        td { padding: 6px; color: #334155; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 8px; font-size: 7pt; font-weight: bold; }
        .badge-entrada { background-color: #d1fae5; color: #065f46; }
        .badge-salida { background-color: #fee2e2; color: #991b1b; }
        .badge-ajuste { background-color: #dbeafe; color: #1e40af; }
        .footer { margin-top: 25px; padding-top: 12px; border-top: 2px solid #e2e8f0; text-align: center; font-size: 7pt; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MOVIMIENTOS DE INVENTARIO</h1>
        <p>Historial de entradas, salidas y ajustes</p>
        <p style="margin-top: 4px;">Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <strong>Período:</strong> {{ $datos['fecha_inicio'] ?? 'N/A' }} - {{ $datos['fecha_fin'] ?? date('d/m/Y') }} |
        <strong>Tipo:</strong> {{ $datos['tipo_movimiento'] ?? 'Todos' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Producto</th>
                <th class="text-center">Tipo</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Stock Anterior</th>
                <th class="text-center">Stock Nuevo</th>
                <th>Usuario</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($datos['movimientos'] ?? [] as $movimiento)
            <tr>
                <td>{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $movimiento->producto->nombre ?? 'N/A' }}</td>
                <td class="text-center">
                    @if($movimiento->tipo_movimiento === 'entrada')
                        <span class="badge badge-entrada">ENTRADA</span>
                    @elseif($movimiento->tipo_movimiento === 'salida')
                        <span class="badge badge-salida">SALIDA</span>
                    @else
                        <span class="badge badge-ajuste">AJUSTE</span>
                    @endif
                </td>
                <td class="text-center">{{ number_format($movimiento->cantidad) }}</td>
                <td class="text-center">{{ number_format($movimiento->stock_anterior ?? 0) }}</td>
                <td class="text-center">{{ number_format($movimiento->stock_nuevo ?? 0) }}</td>
                <td>{{ $movimiento->usuario->name ?? 'Sistema' }}</td>
                <td style="font-size: 7pt;">{{ Str::limit($movimiento->descripcion, 50) }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align: center; padding: 30px; color: #94a3b8;">No hay movimientos registrados</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema de Gestión de Ventas | {{ config('app.name') }}</p>
    </div>
</body>
</html>
