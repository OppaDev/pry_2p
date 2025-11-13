<?php

namespace App\Services;

use App\Models\Factura;
use App\Models\Venta;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DOMDocument;
use Exception;

/**
 * Servicio de Facturación Electrónica SRI Ecuador
 * 
 * Sistema completo de facturación con modo PRUEBA/PRODUCCIÓN
 * Genera XML según estándar SRI, clave de acceso, PDF RIDE
 * 
 * @author Inferno Club - Sistema de Facturación
 * @version 1.0
 */
class FacturaService
{
    /**
     * Configuración SRI desde .env
     */
    private bool $modoPrueba;
    private string $rucEmpresa;
    private string $razonSocialEmpresa;
    private string $nombreComercial;
    private string $direccionMatriz;
    private string $establecimiento;
    private string $puntoEmision;
    private bool $obligadoContabilidad;
    
    public function __construct()
    {
        $this->modoPrueba = env('SRI_MODO_PRUEBA', true);
        $this->rucEmpresa = env('SRI_RUC_EMPRESA', '1234567890001');
        $this->razonSocialEmpresa = env('SRI_RAZON_SOCIAL', 'INFERNO CLUB S.A.');
        $this->nombreComercial = env('SRI_NOMBRE_COMERCIAL', 'Inferno Club');
        $this->direccionMatriz = env('SRI_DIRECCION_MATRIZ', 'Av. Principal 123, Quito');
        $this->establecimiento = env('SRI_ESTABLECIMIENTO', '001');
        $this->puntoEmision = env('SRI_PUNTO_EMISION', '001');
        $this->obligadoContabilidad = env('SRI_OBLIGADO_CONTABILIDAD', true);
    }
    
    /**
     * Generar factura electrónica desde una venta
     * 
     * @param int $ventaId ID de la venta
     * @return Factura
     * @throws Exception
     */
    public function generarFacturaDesdeVenta(int $ventaId): Factura
    {
        return DB::transaction(function () use ($ventaId) {
            // Cargar venta con relaciones
            $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($ventaId);
            
            // Validar que la venta tenga cliente
            if (!$venta->cliente) {
                throw new Exception('La venta debe tener un cliente asignado para generar factura');
            }
            
            // Verificar si ya existe factura para esta venta
            if ($venta->factura) {
                throw new Exception('Esta venta ya tiene una factura generada');
            }
            
            // Verificar que la venta esté completada
            if ($venta->estado !== 'completada') {
                throw new Exception('Solo se pueden facturar ventas completadas');
            }
            
            // Generar número secuencial
            $numeroSecuencial = $this->generarNumeroSecuencial();
            
            // Generar clave de acceso SRI (48 dígitos)
            $claveAcceso = $this->generarClaveAcceso($venta, $numeroSecuencial);
            
            // Calcular totales con IVA
            $totales = $this->calcularTotales($venta);
            
            // Crear registro de factura
            $factura = Factura::create([
                'venta_id' => $venta->id,
                'numero_secuencial' => $numeroSecuencial,
                'clave_acceso_sri' => $claveAcceso,
                'fecha_emision' => Carbon::now(),
                'subtotal' => $totales['subtotal'],
                'impuestos' => $totales['iva'],
                'total' => $totales['total'],
                'estado_autorizacion' => $this->modoPrueba ? 'autorizada' : 'pendiente',
                'xml_factura' => null, // Se generará después
                'respuesta_sri' => null,
            ]);
            
            // Generar XML de la factura
            $xml = $this->generarXML($factura, $venta);
            
            // Guardar XML
            $xmlPath = $this->guardarXML($factura, $xml);
            
            // Actualizar factura con el XML
            $factura->update(['xml_factura' => $xml]);
            
            // En modo prueba, simular autorización inmediata
            if ($this->modoPrueba) {
                $factura->update([
                    'fecha_autorizacion' => Carbon::now(),
                    'numero_autorizacion' => $claveAcceso,
                    'respuesta_sri' => [
                        'estado' => 'AUTORIZADO',
                        'numeroAutorizacion' => $claveAcceso,
                        'fechaAutorizacion' => Carbon::now()->format('d/m/Y H:i:s'),
                        'ambiente' => 'PRUEBAS',
                        'mensajes' => ['Factura procesada correctamente en modo PRUEBA'],
                    ],
                ]);
            }
            
            Log::info("Factura generada: {$numeroSecuencial} para venta {$ventaId}");
            
            return $factura->fresh();
        });
    }
    
    /**
     * Generar número secuencial de factura
     * Formato: 001-001-000000001
     */
    private function generarNumeroSecuencial(): string
    {
        // Obtener el último número secuencial
        $ultimaFactura = Factura::where('numero_secuencial', 'like', "{$this->establecimiento}-{$this->puntoEmision}-%")
            ->orderBy('id', 'desc')
            ->first();
        
        if ($ultimaFactura) {
            // Extraer el número secuencial
            $partes = explode('-', $ultimaFactura->numero_secuencial);
            $ultimoNumero = (int) end($partes);
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }
        
        return sprintf('%s-%s-%09d', $this->establecimiento, $this->puntoEmision, $nuevoNumero);
    }
    
    /**
     * Generar clave de acceso SRI (48 dígitos + dígito verificador)
     * Formato SRI: fecha(8) + tipoComprobante(2) + ruc(13) + ambiente(1) + serie(6) + secuencial(9) + código numérico(8) + tipo emisión(1) + dígito verificador(1)
     */
    private function generarClaveAcceso(Venta $venta, string $numeroSecuencial): string
    {
        $fecha = Carbon::now()->format('dmY'); // ddmmyyyy
        $tipoComprobante = '01'; // 01 = Factura
        $ruc = $this->rucEmpresa;
        $ambiente = $this->modoPrueba ? '1' : '2'; // 1=Pruebas, 2=Producción
        
        // Serie (establecimiento + punto emisión)
        $partes = explode('-', $numeroSecuencial);
        $serie = $partes[0] . $partes[1]; // 001001
        $secuencial = $partes[2]; // 000000001
        
        // Código numérico aleatorio de 8 dígitos
        $codigoNumerico = str_pad(random_int(1, 99999999), 8, '0', STR_PAD_LEFT);
        
        $tipoEmision = '1'; // 1=Normal, 2=Indisponibilidad
        
        // Construir clave sin dígito verificador (48 dígitos)
        $claveSinVerificador = $fecha . $tipoComprobante . $ruc . $ambiente . $serie . $secuencial . $codigoNumerico . $tipoEmision;
        
        // Calcular dígito verificador con módulo 11
        $digitoVerificador = $this->calcularDigitoVerificador($claveSinVerificador);
        
        return $claveSinVerificador . $digitoVerificador;
    }
    
    /**
     * Calcular dígito verificador usando algoritmo módulo 11
     * Según especificación SRI Ecuador
     */
    private function calcularDigitoVerificador(string $clave): int
    {
        $factor = 7;
        $suma = 0;
        
        for ($i = 0; $i < strlen($clave); $i++) {
            $suma += (int)$clave[$i] * $factor;
            $factor--;
            if ($factor == 1) {
                $factor = 7;
            }
        }
        
        $modulo = $suma % 11;
        $digitoVerificador = 11 - $modulo;
        
        if ($digitoVerificador == 11) {
            return 0;
        } elseif ($digitoVerificador == 10) {
            return 1;
        }
        
        return $digitoVerificador;
    }
    
    /**
     * Calcular totales de la factura (subtotal, IVA, total)
     */
    private function calcularTotales(Venta $venta): array
    {
        $subtotal = 0;
        
        foreach ($venta->detalles as $detalle) {
            $subtotal += $detalle->precio_unitario * $detalle->cantidad;
        }
        
        // IVA 12% (puede ser configurable)
        $tarifaIva = 12;
        $iva = $subtotal * ($tarifaIva / 100);
        $total = $subtotal + $iva;
        
        return [
            'subtotal' => round($subtotal, 2),
            'iva' => round($iva, 2),
            'total' => round($total, 2),
            'tarifa_iva' => $tarifaIva,
        ];
    }
    
    /**
     * Generar XML de la factura según formato SRI Ecuador
     */
    private function generarXML(Factura $factura, Venta $venta): string
    {
        $cliente = $venta->cliente;
        $totales = $this->calcularTotales($venta);
        
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;
        
        // Elemento raíz
        $root = $xml->createElement('factura');
        $root->setAttribute('id', 'comprobante');
        $root->setAttribute('version', '1.0.0');
        $xml->appendChild($root);
        
        // Información tributaria
        $infoTributaria = $xml->createElement('infoTributaria');
        $this->addElement($xml, $infoTributaria, 'ambiente', $this->modoPrueba ? '1' : '2');
        $this->addElement($xml, $infoTributaria, 'tipoEmision', '1');
        $this->addElement($xml, $infoTributaria, 'razonSocial', $this->razonSocialEmpresa);
        $this->addElement($xml, $infoTributaria, 'nombreComercial', $this->nombreComercial);
        $this->addElement($xml, $infoTributaria, 'ruc', $this->rucEmpresa);
        $this->addElement($xml, $infoTributaria, 'claveAcceso', $factura->clave_acceso_sri);
        $this->addElement($xml, $infoTributaria, 'codDoc', '01');
        $this->addElement($xml, $infoTributaria, 'estab', $this->establecimiento);
        $this->addElement($xml, $infoTributaria, 'ptoEmi', $this->puntoEmision);
        $this->addElement($xml, $infoTributaria, 'secuencial', substr($factura->numero_secuencial, -9));
        $this->addElement($xml, $infoTributaria, 'dirMatriz', $this->direccionMatriz);
        $root->appendChild($infoTributaria);
        
        // Información de la factura
        $infoFactura = $xml->createElement('infoFactura');
        $this->addElement($xml, $infoFactura, 'fechaEmision', $factura->fecha_emision->format('d/m/Y'));
        $this->addElement($xml, $infoFactura, 'dirEstablecimiento', $this->direccionMatriz);
        $this->addElement($xml, $infoFactura, 'obligadoContabilidad', $this->obligadoContabilidad ? 'SI' : 'NO');
        $this->addElement($xml, $infoFactura, 'tipoIdentificacionComprador', $this->obtenerTipoIdentificacion($cliente));
        $this->addElement($xml, $infoFactura, 'razonSocialComprador', $cliente->nombre_completo);
        $this->addElement($xml, $infoFactura, 'identificacionComprador', $cliente->identificacion);
        $this->addElement($xml, $infoFactura, 'totalSinImpuestos', number_format($totales['subtotal'], 2, '.', ''));
        $this->addElement($xml, $infoFactura, 'totalDescuento', '0.00');
        
        // Total con impuestos
        $totalConImpuestos = $xml->createElement('totalConImpuestos');
        $totalImpuesto = $xml->createElement('totalImpuesto');
        $this->addElement($xml, $totalImpuesto, 'codigo', '2'); // 2=IVA
        $this->addElement($xml, $totalImpuesto, 'codigoPorcentaje', '2'); // 2=12%
        $this->addElement($xml, $totalImpuesto, 'baseImponible', number_format($totales['subtotal'], 2, '.', ''));
        $this->addElement($xml, $totalImpuesto, 'valor', number_format($totales['iva'], 2, '.', ''));
        $totalConImpuestos->appendChild($totalImpuesto);
        $infoFactura->appendChild($totalConImpuestos);
        
        $this->addElement($xml, $infoFactura, 'propina', '0.00');
        $this->addElement($xml, $infoFactura, 'importeTotal', number_format($totales['total'], 2, '.', ''));
        $this->addElement($xml, $infoFactura, 'moneda', 'DOLAR');
        $root->appendChild($infoFactura);
        
        // Detalles de la factura
        $detalles = $xml->createElement('detalles');
        foreach ($venta->detalles as $detalle) {
            $detalleNode = $xml->createElement('detalle');
            $this->addElement($xml, $detalleNode, 'codigoPrincipal', $detalle->producto->codigo ?? 'PROD');
            $this->addElement($xml, $detalleNode, 'descripcion', $detalle->producto->nombre ?? 'Producto');
            $this->addElement($xml, $detalleNode, 'cantidad', number_format($detalle->cantidad, 2, '.', ''));
            $this->addElement($xml, $detalleNode, 'precioUnitario', number_format($detalle->precio_unitario, 2, '.', ''));
            $this->addElement($xml, $detalleNode, 'descuento', '0.00');
            $this->addElement($xml, $detalleNode, 'precioTotalSinImpuesto', number_format($detalle->precio_unitario * $detalle->cantidad, 2, '.', ''));
            
            // Impuestos del detalle
            $impuestosDetalle = $xml->createElement('impuestos');
            $impuesto = $xml->createElement('impuesto');
            $this->addElement($xml, $impuesto, 'codigo', '2');
            $this->addElement($xml, $impuesto, 'codigoPorcentaje', '2');
            $this->addElement($xml, $impuesto, 'tarifa', $totales['tarifa_iva']);
            $baseImponible = $detalle->precio_unitario * $detalle->cantidad;
            $this->addElement($xml, $impuesto, 'baseImponible', number_format($baseImponible, 2, '.', ''));
            $this->addElement($xml, $impuesto, 'valor', number_format($baseImponible * ($totales['tarifa_iva'] / 100), 2, '.', ''));
            $impuestosDetalle->appendChild($impuesto);
            $detalleNode->appendChild($impuestosDetalle);
            
            $detalles->appendChild($detalleNode);
        }
        $root->appendChild($detalles);
        
        // Información adicional
        $infoAdicional = $xml->createElement('infoAdicional');
        if ($cliente->correo) {
            $campoAdicional = $xml->createElement('campoAdicional', $cliente->correo);
            $campoAdicional->setAttribute('nombre', 'Email');
            $infoAdicional->appendChild($campoAdicional);
        }
        if ($cliente->telefono) {
            $campoAdicional = $xml->createElement('campoAdicional', $cliente->telefono);
            $campoAdicional->setAttribute('nombre', 'Teléfono');
            $infoAdicional->appendChild($campoAdicional);
        }
        $root->appendChild($infoAdicional);
        
        return $xml->saveXML();
    }
    
    /**
     * Agregar elemento al XML
     */
    private function addElement(DOMDocument $xml, $parent, string $name, string $value): void
    {
        $element = $xml->createElement($name, htmlspecialchars($value, ENT_XML1, 'UTF-8'));
        $parent->appendChild($element);
    }
    
    /**
     * Obtener tipo de identificación según formato SRI
     */
    private function obtenerTipoIdentificacion(Cliente $cliente): string
    {
        switch ($cliente->tipo_identificacion) {
            case 'cedula':
                return '05'; // Cédula
            case 'ruc':
                return '04'; // RUC
            case 'pasaporte':
                return '06'; // Pasaporte
            default:
                return '07'; // Consumidor Final
        }
    }
    
    /**
     * Guardar XML en storage
     */
    private function guardarXML(Factura $factura, string $xml): string
    {
        $filename = "factura_{$factura->numero_secuencial}_{$factura->clave_acceso_sri}.xml";
        $path = "facturas/xml/{$filename}";
        
        Storage::disk('local')->put($path, $xml);
        
        return $path;
    }
    
    /**
     * Generar PDF RIDE de la factura
     * RIDE = Representación Impresa de Documento Electrónico
     */
    public function generarRIDE(Factura $factura): string
    {
        // Cargar venta con relaciones
        $venta = $factura->venta()->with(['cliente', 'detalles.producto'])->first();
        $cliente = $venta->cliente;
        $totales = $this->calcularTotales($venta);
        
        // En una implementación real, aquí se usaría una librería como TCPDF o DomPDF
        // Por ahora, generamos un HTML simple que puede convertirse a PDF
        
        $html = view('facturas.ride', [
            'factura' => $factura,
            'venta' => $venta,
            'cliente' => $cliente,
            'totales' => $totales,
            'empresa' => [
                'ruc' => $this->rucEmpresa,
                'razon_social' => $this->razonSocialEmpresa,
                'nombre_comercial' => $this->nombreComercial,
                'direccion' => $this->direccionMatriz,
            ],
        ])->render();
        
        // Guardar HTML como PDF temporal
        $filename = "factura_{$factura->numero_secuencial}_RIDE.html";
        $path = "facturas/pdf/{$filename}";
        Storage::disk('local')->put($path, $html);
        
        return $path;
    }
    
    /**
     * Enviar factura al SRI (solo en modo producción)
     */
    public function enviarSRI(Factura $factura): bool
    {
        if ($this->modoPrueba) {
            // En modo prueba, simular respuesta exitosa
            Log::info("MODO PRUEBA: Factura {$factura->numero_secuencial} autorizada automáticamente");
            return true;
        }
        
        // En producción, aquí se implementaría la comunicación real con el SRI
        // Usando SOAP o REST API según disponibilidad
        
        throw new Exception('Funcionalidad de envío al SRI no implementada. Configure certificado digital y credenciales.');
    }
    
    /**
     * Anular factura
     */
    public function anularFactura(Factura $factura): bool
    {
        if ($factura->estaAnulada()) {
            throw new Exception('La factura ya está anulada');
        }
        
        return DB::transaction(function () use ($factura) {
            $factura->update([
                'estado_autorizacion' => 'anulada',
            ]);
            
            Log::info("Factura anulada: {$factura->numero_secuencial}");
            
            return true;
        });
    }
    
    /**
     * Obtener estadísticas de facturación
     */
    public function estadisticasFacturacion(): array
    {
        return [
            'total_facturas' => Factura::count(),
            'facturas_autorizadas' => Factura::autorizadas()->count(),
            'facturas_pendientes' => Factura::pendientes()->count(),
            'facturas_rechazadas' => Factura::rechazadas()->count(),
            'total_facturado_hoy' => Factura::whereDate('fecha_emision', today())
                ->where('estado_autorizacion', 'autorizada')
                ->sum('total'),
            'total_facturado_mes' => Factura::whereMonth('fecha_emision', now()->month)
                ->where('estado_autorizacion', 'autorizada')
                ->sum('total'),
        ];
    }
}
