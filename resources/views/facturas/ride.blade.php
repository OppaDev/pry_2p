<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RIDE - Factura {{ $factura->numero_secuencial }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 15px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .header-left, .header-right {
            width: 48%;
        }
        .header-right {
            text-align: center;
            border: 1px solid #000;
            padding: 10px;
        }
        .ruc {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .factura-num {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }
        .section-title {
            background-color: #e0e0e0;
            padding: 5px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
            border: 1px solid #000;
        }
        .info-row {
            display: flex;
            margin-bottom: 3px;
        }
        .info-label {
            width: 30%;
            font-weight: bold;
        }
        .info-value {
            width: 70%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        table th {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totales {
            margin-top: 10px;
            float: right;
            width: 40%;
        }
        .totales table {
            margin-top: 0;
        }
        .total-final {
            font-weight: bold;
            font-size: 14px;
        }
        .clave-acceso {
            margin-top: 20px;
            clear: both;
            padding-top: 10px;
            border-top: 1px solid #000;
        }
        .clave-acceso-num {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            word-break: break-all;
            margin-top: 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .autorizacion {
            background-color: #d4edda;
            border: 2px solid #28a745;
            padding: 10px;
            margin-top: 15px;
            text-align: center;
        }
        .autorizacion .numero {
            font-weight: bold;
            font-size: 11px;
            margin: 5px 0;
        }
        @media print {
            body {
                padding: 0;
            }
            .container {
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <div class="header-left">
                <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">
                    {{ $empresa['razon_social'] }}
                </div>
                <div style="font-weight: bold;">{{ $empresa['nombre_comercial'] }}</div>
                <div>{{ $empresa['direccion'] }}</div>
                <div style="margin-top: 5px;">
                    <strong>Obligado a llevar contabilidad:</strong> {{ env('SRI_OBLIGADO_CONTABILIDAD', true) ? 'SI' : 'NO' }}
                </div>
            </div>
            <div class="header-right">
                <div class="ruc">RUC: {{ $empresa['ruc'] }}</div>
                <div style="font-size: 14px; font-weight: bold; margin: 5px 0;">FACTURA</div>
                <div class="factura-num">No. {{ $factura->numero_secuencial }}</div>
                <div style="margin-top: 10px; font-size: 11px;">
                    @if($factura->numero_autorizacion)
                        <div><strong>AUTORIZADO</strong></div>
                        <div>{{ \Carbon\Carbon::parse($factura->fecha_autorizacion)->format('d/m/Y H:i:s') }}</div>
                    @else
                        <div><strong>{{ strtoupper($factura->estado_autorizacion) }}</strong></div>
                    @endif
                </div>
                <div style="margin-top: 5px; font-size: 10px;">
                    <strong>Ambiente:</strong> {{ env('SRI_MODO_PRUEBA', true) ? 'PRUEBAS' : 'PRODUCCIÓN' }}
                </div>
                <div style="font-size: 10px;">
                    <strong>Emisión:</strong> NORMAL
                </div>
            </div>
        </div>

        <!-- INFORMACIÓN DEL CLIENTE -->
        <div class="section-title">INFORMACIÓN DEL CLIENTE</div>
        <div class="info-row">
            <div class="info-label">Razón Social:</div>
            <div class="info-value">{{ $cliente->nombre_completo }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Identificación:</div>
            <div class="info-value">{{ $cliente->identificacion }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Fecha de Emisión:</div>
            <div class="info-value">{{ $factura->fecha_emision->format('d/m/Y') }}</div>
        </div>
        @if($cliente->direccion)
        <div class="info-row">
            <div class="info-label">Dirección:</div>
            <div class="info-value">{{ $cliente->direccion }}</div>
        </div>
        @endif
        @if($cliente->telefono)
        <div class="info-row">
            <div class="info-label">Teléfono:</div>
            <div class="info-value">{{ $cliente->telefono }}</div>
        </div>
        @endif
        @if($cliente->correo)
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value">{{ $cliente->correo }}</div>
        </div>
        @endif

        <!-- DETALLE DE PRODUCTOS -->
        <div class="section-title">DETALLE DE PRODUCTOS / SERVICIOS</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Código</th>
                    <th style="width: 35%;">Descripción</th>
                    <th class="text-center" style="width: 10%;">Cantidad</th>
                    <th class="text-right" style="width: 15%;">Precio Unitario</th>
                    <th class="text-right" style="width: 10%;">Descuento</th>
                    <th class="text-right" style="width: 15%;">Precio Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->codigo ?? 'N/A' }}</td>
                    <td>{{ $detalle->producto->nombre ?? 'Producto' }}</td>
                    <td class="text-center">{{ number_format($detalle->cantidad, 2) }}</td>
                    <td class="text-right">${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td class="text-right">$0.00</td>
                    <td class="text-right">${{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- INFORMACIÓN ADICIONAL -->
        @if($cliente->correo || $cliente->telefono)
        <div class="section-title" style="margin-top: 15px;">INFORMACIÓN ADICIONAL</div>
        @if($cliente->correo)
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value">{{ $cliente->correo }}</div>
        </div>
        @endif
        @if($cliente->telefono)
        <div class="info-row">
            <div class="info-label">Teléfono:</div>
            <div class="info-value">{{ $cliente->telefono }}</div>
        </div>
        @endif
        @endif

        <!-- TOTALES -->
        <div class="totales">
            <table>
                <tr>
                    <td><strong>SUBTOTAL SIN IMPUESTOS:</strong></td>
                    <td class="text-right">${{ number_format($totales['subtotal'], 2) }}</td>
                </tr>
                <tr>
                    <td><strong>SUBTOTAL 12%:</strong></td>
                    <td class="text-right">${{ number_format($totales['subtotal'], 2) }}</td>
                </tr>
                <tr>
                    <td><strong>SUBTOTAL 0%:</strong></td>
                    <td class="text-right">$0.00</td>
                </tr>
                <tr>
                    <td><strong>DESCUENTO:</strong></td>
                    <td class="text-right">$0.00</td>
                </tr>
                <tr>
                    <td><strong>IVA 12%:</strong></td>
                    <td class="text-right">${{ number_format($totales['iva'], 2) }}</td>
                </tr>
                <tr class="total-final">
                    <td><strong>TOTAL:</strong></td>
                    <td class="text-right">${{ number_format($totales['total'], 2) }}</td>
                </tr>
            </table>
        </div>

        <div style="clear: both;"></div>

        <!-- CLAVE DE ACCESO -->
        <div class="clave-acceso">
            <div style="font-weight: bold; margin-bottom: 5px;">CLAVE DE ACCESO</div>
            <div class="clave-acceso-num">{{ $factura->clave_acceso_sri }}</div>
        </div>

        <!-- AUTORIZACIÓN -->
        @if($factura->numero_autorizacion)
        <div class="autorizacion">
            <div style="font-size: 12px; font-weight: bold;">AUTORIZACIÓN SRI</div>
            <div class="numero">{{ $factura->numero_autorizacion }}</div>
            <div style="font-size: 10px;">
                Fecha y Hora de Autorización: {{ \Carbon\Carbon::parse($factura->fecha_autorizacion)->format('d/m/Y H:i:s') }}
            </div>
            <div style="font-size: 10px; margin-top: 5px;">
                Ambiente: {{ env('SRI_MODO_PRUEBA', true) ? 'PRUEBAS' : 'PRODUCCIÓN' }} - 
                Emisión: NORMAL - 
                Clave de Acceso: {{ $factura->clave_acceso_sri }}
            </div>
        </div>
        @endif

        <!-- FOOTER -->
        <div class="footer">
            <p>{{ $empresa['razon_social'] }} - RUC: {{ $empresa['ruc'] }}</p>
            <p>{{ $empresa['direccion'] }}</p>
            @if(env('SRI_MODO_PRUEBA', true))
            <p style="color: #dc3545; font-weight: bold; margin-top: 10px;">
                ⚠️ DOCUMENTO GENERADO EN MODO PRUEBA - NO VÁLIDO PARA EFECTOS TRIBUTARIOS
            </p>
            @endif
        </div>
    </div>
</body>
</html>
