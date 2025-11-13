<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos con Bajo Stock</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #ef4444; }
        .header h1 { color: #dc2626; font-size: 20pt; margin-bottom: 5px; }
        .header p { color: #64748b; font-size: 9pt; }
        .alert { background-color: #fee2e2; border-left: 4px solid #dc2626; padding: 12px; margin-bottom: 20px; border-radius: 4px; font-size: 9pt; }
        table { width: 100%; border-collapse: collapse; font-size: 9pt; }
        thead { background-color: #dc2626; color: white; }
        th { padding: 8px 6px; text-align: left; font-size: 8pt; font-weight: bold; text-transform: uppercase; }
        tbody tr { border-bottom: 1px solid #e2e8f0; }
        tbody tr:nth-child(even) { background-color: #fef2f2; }
        td { padding: 6px; color: #334155; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 10px; font-size: 7pt; font-weight: bold; }
        .badge-critico { background-color: #fee2e2; color: #991b1b; }
        .badge-bajo { background-color: #fed7aa; color: #9a3412; }
        .footer { margin-top: 25px; padding-top: 12px; border-top: 2px solid #e2e8f0; text-align: center; font-size: 7pt; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PRODUCTOS CON BAJO STOCK</h1>
        <p>Alertas de inventario crítico</p>
        <p style="margin-top: 4px;">Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="alert">
        <strong>ATENCIÓN:</strong> Los siguientes productos requieren reabastecimiento urgente.
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th class="text-center">Stock Actual</th>
                <th class="text-center">Stock Mínimo</th>
                <th class="text-center">Faltante</th>
                <th class="text-center">Prioridad</th>
            </tr>
        </thead>
        <tbody>
            @forelse($datos['productos'] ?? [] as $producto)
            <tr>
                <td><strong>{{ $producto->codigo }}</strong></td>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->categoria->nombre ?? 'N/A' }}</td>
                <td class="text-center">{{ number_format($producto->stock_actual) }}</td>
                <td class="text-center">{{ number_format($producto->stock_minimo) }}</td>
                <td class="text-center">{{ number_format($producto->stock_minimo - $producto->stock_actual) }}</td>
                <td class="text-center">
                    @if($producto->stock_actual == 0)
                        <span class="badge badge-critico">CRÍTICO</span>
                    @else
                        <span class="badge badge-bajo">BAJO</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">✓ Todos los productos tienen stock adecuado</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema de Gestión de Ventas | {{ config('app.name') }}</p>
    </div>
</body>
</html>
