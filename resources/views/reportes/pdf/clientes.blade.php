<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Clientes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #3b82f6;
        }
        
        .header h1 {
            color: #1e40af;
            font-size: 20pt;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #64748b;
            font-size: 10pt;
        }
        
        .info-section {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            color: #475569;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-card.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .stat-card.blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
        
        .stat-label {
            font-size: 9pt;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 18pt;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        thead {
            background-color: #1e40af;
            color: white;
        }
        
        th {
            padding: 10px 8px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        tbody tr:hover {
            background-color: #e0f2fe;
        }
        
        td {
            padding: 8px;
            font-size: 9pt;
            color: #334155;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 8pt;
            color: #94a3b8;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE CLIENTES</h1>
        <p>Análisis de clientes y comportamiento de compra</p>
        <p style="margin-top: 5px;">Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Información del Reporte -->
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Período:</span>
            <span>Todos los registros</span>
        </div>
        <div class="info-row">
            <span class="info-label">Estado:</span>
            <span>{{ $filtros['estado'] ?? 'Todos' }}</span>
        </div>
    </div>

    <!-- Estadísticas -->
    @if(isset($estadisticas))
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Clientes</div>
            <div class="stat-value">{{ number_format($estadisticas['total_clientes'] ?? 0) }}</div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Con Compras</div>
            <div class="stat-value">{{ number_format($estadisticas['clientes_con_compras'] ?? 0) }}</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-label">Total Ventas</div>
            <div class="stat-value">${{ number_format($estadisticas['total_ventas_generadas'] ?? 0, 2) }}</div>
        </div>
    </div>
    @endif

    <!-- Tabla de Clientes -->
    <table>
        <thead>
            <tr>
                <th>Identificación</th>
                <th>Cliente</th>
                <th>Contacto</th>
                <th class="text-center">Compras</th>
                <th class="text-right">Total Gastado</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($clientes ?? [] as $cliente)
            <tr>
                <td>
                    <strong>{{ strtoupper($cliente->tipo_identificacion) }}</strong><br>
                    {{ $cliente->identificacion }}
                </td>
                <td>
                    <strong>{{ $cliente->nombre_completo }}</strong><br>
                    <span style="color: #64748b; font-size: 8pt;">{{ $cliente->edad }} años</span>
                </td>
                <td>
                    @if($cliente->telefono)
                         {{ $cliente->telefono }}<br>
                    @endif
                    @if($cliente->correo)
                         {{ $cliente->correo }}
                    @endif
                </td>
                <td class="text-center">
                    {{ number_format($cliente->ventas_count ?? 0) }}
                </td>
                <td class="text-right">
                    <strong>${{ number_format($cliente->total_gastado ?? 0, 2) }}</strong>
                </td>
                <td class="text-center">
                    @if($cliente->estado === 'activo')
                        <span class="badge badge-success">ACTIVO</span>
                    @else
                        <span class="badge badge-danger">INACTIVO</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="no-data">
                    No se encontraron clientes con los filtros seleccionados
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema de Gestión de Ventas | {{ config('app.name') }}</p>
        <p>Este documento es un reporte generado automáticamente</p>
    </div>
</body>
</html>
