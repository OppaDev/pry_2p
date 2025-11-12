<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\VentaService;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\User;
use App\Models\MovimientoInventario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class VentaServiceTest extends TestCase
{
    use RefreshDatabase;

    protected VentaService $ventaService;
    protected User $vendedor;
    protected Cliente $cliente;
    protected Producto $producto;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->ventaService = new VentaService();
        
        // Crear usuario vendedor
        $this->vendedor = User::factory()->create([
            'name' => 'Vendedor Test',
            'email' => 'vendedor@test.com',
        ]);
        
        // Crear cliente mayor de edad
        $this->cliente = Cliente::factory()->create([
            'nombres' => 'Juan',
            'apellidos' => 'Pérez',
            'fecha_nacimiento' => now()->subYears(25),
            'estado' => 'activo',
        ]);
        
        // Crear categoría
        $categoria = Categoria::create([
            'nombre' => 'Whisky',
            'descripcion' => 'Bebidas destiladas',
        ]);
        
        // Crear producto con stock
        $this->producto = Producto::factory()->create([
            'codigo' => 'PROD-001',
            'nombre' => 'Whisky Test',
            'marca' => 'Test Brand',
            'precio' => 50.00,
            'stock_actual' => 100,
            'stock_minimo' => 10,
            'estado' => 'activo',
            'categoria_id' => $categoria->id,
        ]);
    }

    /** @test */
    public function puede_procesar_venta_exitosamente()
    {
        $datos = [
            'cliente_id' => $this->cliente->id,
            'metodo_pago' => 'efectivo',
            'observaciones' => 'Venta de prueba',
            'items' => [
                [
                    'producto_id' => $this->producto->id,
                    'cantidad' => 2,
                ]
            ]
        ];
        
        $venta = $this->ventaService->procesarVenta($datos, $this->vendedor);
        
        // Verificar que la venta se creó
        $this->assertInstanceOf(Venta::class, $venta);
        $this->assertEquals('completada', $venta->estado);
        $this->assertEquals($this->cliente->id, $venta->cliente_id);
        $this->assertEquals($this->vendedor->id, $venta->vendedor_id);
        
        // Verificar cálculos (2 * 50 = 100, IVA 15% = 15, Total = 115)
        $this->assertEquals(100.00, $venta->subtotal);
        $this->assertEquals(15.00, $venta->impuestos);
        $this->assertEquals(115.00, $venta->total);
        
        // Verificar que se creó el detalle
        $this->assertCount(1, $venta->detalles);
        $this->assertEquals(2, $venta->detalles->first()->cantidad);
        
        // Verificar que se actualizó el stock (100 - 2 = 98)
        $this->producto->refresh();
        $this->assertEquals(98, $this->producto->stock_actual);
        
        // Verificar que se creó el movimiento de inventario
        $movimiento = MovimientoInventario::where('producto_id', $this->producto->id)->first();
        $this->assertNotNull($movimiento);
        $this->assertEquals('salida', $movimiento->tipo);
        $this->assertEquals(-2, $movimiento->cantidad);
    }

    /** @test */
    public function no_permite_venta_a_menor_de_edad()
    {
        $clienteMenor = Cliente::factory()->create([
            'nombres' => 'Pedro',
            'apellidos' => 'González',
            'fecha_nacimiento' => now()->subYears(16), // 16 años
            'estado' => 'activo',
        ]);
        
        $datos = [
            'cliente_id' => $clienteMenor->id,
            'metodo_pago' => 'efectivo',
            'items' => [
                [
                    'producto_id' => $this->producto->id,
                    'cantidad' => 1,
                ]
            ]
        ];
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('menor de edad');
        
        $this->ventaService->procesarVenta($datos, $this->vendedor);
    }

    /** @test */
    public function no_permite_venta_sin_stock_suficiente()
    {
        $datos = [
            'cliente_id' => $this->cliente->id,
            'metodo_pago' => 'efectivo',
            'items' => [
                [
                    'producto_id' => $this->producto->id,
                    'cantidad' => 150, // Más del stock disponible (100)
                ]
            ]
        ];
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Stock insuficiente');
        
        $this->ventaService->procesarVenta($datos, $this->vendedor);
    }

    /** @test */
    public function puede_anular_venta_y_restaurar_stock()
    {
        // Primero crear una venta
        $datos = [
            'cliente_id' => $this->cliente->id,
            'metodo_pago' => 'efectivo',
            'items' => [
                [
                    'producto_id' => $this->producto->id,
                    'cantidad' => 5,
                ]
            ]
        ];
        
        $venta = $this->ventaService->procesarVenta($datos, $this->vendedor);
        
        // Verificar stock después de venta (100 - 5 = 95)
        $this->producto->refresh();
        $this->assertEquals(95, $this->producto->stock_actual);
        
        // Anular la venta
        $ventaAnulada = $this->ventaService->anularVenta(
            $venta->id, 
            'Cliente solicitó cancelación', 
            $this->vendedor
        );
        
        // Verificar que la venta está anulada
        $this->assertEquals('anulada', $ventaAnulada->estado);
        
        // Verificar que el stock se restauró (95 + 5 = 100)
        $this->producto->refresh();
        $this->assertEquals(100, $this->producto->stock_actual);
        
        // Verificar movimiento de devolución
        $movimientos = MovimientoInventario::where('producto_id', $this->producto->id)
            ->where('tipo', 'ingreso')
            ->get();
        $this->assertCount(1, $movimientos);
    }

    /** @test */
    public function calcula_totales_correctamente()
    {
        $items = [
            [
                'producto_id' => $this->producto->id,
                'cantidad' => 3,
            ]
        ];
        
        $totales = $this->ventaService->calcularTotales($items);
        
        // 3 * 50 = 150
        $this->assertEquals(150.00, $totales['subtotal']);
        // 150 * 0.15 = 22.50
        $this->assertEquals(22.50, $totales['impuestos']);
        // 150 + 22.50 = 172.50
        $this->assertEquals(172.50, $totales['total']);
    }

    /** @test */
    public function verifica_disponibilidad_de_productos()
    {
        $items = [
            [
                'producto_id' => $this->producto->id,
                'cantidad' => 10, // Cantidad válida
            ]
        ];
        
        $resultado = $this->ventaService->verificarDisponibilidad($items);
        
        $this->assertTrue($resultado['disponible']);
        $this->assertEmpty($resultado['errores']);
    }

    /** @test */
    public function detecta_productos_sin_stock()
    {
        $items = [
            [
                'producto_id' => $this->producto->id,
                'cantidad' => 200, // Más del stock disponible
            ]
        ];
        
        $resultado = $this->ventaService->verificarDisponibilidad($items);
        
        $this->assertFalse($resultado['disponible']);
        $this->assertNotEmpty($resultado['errores']);
        $this->assertStringContainsString('Stock insuficiente', $resultado['errores'][0]);
    }

    /** @test */
    public function genera_numero_secuencial_unico()
    {
        $datos = [
            'cliente_id' => $this->cliente->id,
            'metodo_pago' => 'efectivo',
            'items' => [
                [
                    'producto_id' => $this->producto->id,
                    'cantidad' => 1,
                ]
            ]
        ];
        
        $venta1 = $this->ventaService->procesarVenta($datos, $this->vendedor);
        $venta2 = $this->ventaService->procesarVenta($datos, $this->vendedor);
        
        // Verificar que los números secuenciales son diferentes
        $this->assertNotEquals($venta1->numero_secuencial, $venta2->numero_secuencial);
        
        // Verificar formato VTA-YYYYMMDD-XXXX
        $this->assertMatchesRegularExpression('/^VTA-\d{8}-\d{4}$/', $venta1->numero_secuencial);
    }

    /** @test */
    public function obtiene_estadisticas_de_ventas()
    {
        // Crear varias ventas
        for ($i = 0; $i < 3; $i++) {
            $this->ventaService->procesarVenta([
                'cliente_id' => $this->cliente->id,
                'metodo_pago' => 'efectivo',
                'items' => [
                    [
                        'producto_id' => $this->producto->id,
                        'cantidad' => 1,
                    ]
                ]
            ], $this->vendedor);
        }
        
        $estadisticas = $this->ventaService->obtenerEstadisticas();
        
        $this->assertEquals(3, $estadisticas['total_ventas']);
        $this->assertGreaterThan(0, $estadisticas['total_ingresos']);
        $this->assertGreaterThan(0, $estadisticas['promedio_venta']);
    }

    /** @test */
    public function no_permite_venta_de_producto_inactivo()
    {
        // Inactivar el producto
        $this->producto->estado = 'inactivo';
        $this->producto->save();
        
        $datos = [
            'cliente_id' => $this->cliente->id,
            'metodo_pago' => 'efectivo',
            'items' => [
                [
                    'producto_id' => $this->producto->id,
                    'cantidad' => 1,
                ]
            ]
        ];
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('no está activo');
        
        $this->ventaService->procesarVenta($datos, $this->vendedor);
    }

    /** @test */
    public function procesa_venta_con_multiples_productos()
    {
        // Crear segundo producto
        $producto2 = Producto::factory()->create([
            'codigo' => 'PROD-002',
            'nombre' => 'Ron Test',
            'precio' => 30.00,
            'stock_actual' => 50,
            'estado' => 'activo',
            'categoria_id' => $this->producto->categoria_id,
        ]);
        
        $datos = [
            'cliente_id' => $this->cliente->id,
            'metodo_pago' => 'tarjeta',
            'items' => [
                [
                    'producto_id' => $this->producto->id,
                    'cantidad' => 2,
                ],
                [
                    'producto_id' => $producto2->id,
                    'cantidad' => 3,
                ]
            ]
        ];
        
        $venta = $this->ventaService->procesarVenta($datos, $this->vendedor);
        
        // Verificar totales (2*50 + 3*30 = 190, IVA = 28.50, Total = 218.50)
        $this->assertEquals(190.00, $venta->subtotal);
        $this->assertEquals(28.50, $venta->impuestos);
        $this->assertEquals(218.50, $venta->total);
        
        // Verificar detalles
        $this->assertCount(2, $venta->detalles);
        
        // Verificar stock actualizado
        $this->producto->refresh();
        $producto2->refresh();
        $this->assertEquals(98, $this->producto->stock_actual);
        $this->assertEquals(47, $producto2->stock_actual);
    }
}
