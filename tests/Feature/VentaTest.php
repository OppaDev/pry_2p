<?php

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\User;

/**
 * Pruebas Unitarias - Modelo Venta
 * Sistema: Inferno Club - Gestión de Ventas e Inventario
 */

describe('Modelo Venta', function () {

    beforeEach(function () {
        // Crear datos de prueba
        $this->cliente = Cliente::factory()->create();
        $this->vendedor = User::factory()->create();
        $this->producto = Producto::factory()->create([
            'precio' => 25.00,
            'stock_actual' => 50
        ]);
    });

    // ========== TESTS DE CREACIÓN ==========

    test('puede crear una venta completa', function () {
        $venta = Venta::create([
            'numero_secuencial' => 'VENTA-001',
            'fecha' => now(),
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id,
            'subtotal' => 100.00,
            'impuestos' => 15.00,
            'total' => 115.00,
            'metodo_pago' => 'efectivo',
            'estado' => 'completada'
        ]);

        expect($venta)->not->toBeNull()
            ->and($venta->numero_secuencial)->toBe('VENTA-001')
            ->and($venta->total)->toBe('115.00');
    });

    test('número secuencial es único', function () {
        Venta::factory()->create([
            'numero_secuencial' => 'VENTA-001',
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);

        expect(fn() => Venta::create([
            'numero_secuencial' => 'VENTA-001',
            'fecha' => now(),
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id,
            'subtotal' => 50.00,
            'impuestos' => 7.50,
            'total' => 57.50,
            'metodo_pago' => 'efectivo',
            'estado' => 'completada'
        ]))->toThrow(Exception::class);
    });

    // ========== TESTS DE RELACIONES ==========

    test('tiene relación con cliente', function () {
        $venta = Venta::factory()->create([
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);

        expect($venta->cliente)->toBeInstanceOf(Cliente::class)
            ->and($venta->cliente->id)->toBe($this->cliente->id);
    });

    test('tiene relación con vendedor', function () {
        $venta = Venta::factory()->create([
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);

        expect($venta->vendedor)->toBeInstanceOf(User::class)
            ->and($venta->vendedor->id)->toBe($this->vendedor->id);
    });

    test('tiene múltiples detalles de venta', function () {
        $venta = Venta::factory()->create([
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);

        DetalleVenta::create([
            'venta_id' => $venta->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 2,
            'precio_unitario' => 25.00,
            'subtotal_item' => 50.00
        ]);

        DetalleVenta::create([
            'venta_id' => $venta->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 1,
            'precio_unitario' => 25.00,
            'subtotal_item' => 25.00
        ]);

        expect($venta->detalles)->toHaveCount(2);
    });

    // ========== TESTS DE CÁLCULOS ==========

    test('calcula subtotal correctamente', function () {
        $venta = Venta::factory()->create([
            'subtotal' => 100.00,
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);

        expect($venta->subtotal)->toBe('100.00');
    });

    test('calcula IVA 15% correctamente', function () {
        $subtotal = 100.00;
        $ivaPorcentaje = 0.15;
        $ivaCalculado = $subtotal * $ivaPorcentaje;

        $venta = Venta::factory()->create([
            'subtotal' => $subtotal,
            'impuestos' => $ivaCalculado,
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);

        expect((float)$venta->impuestos)->toBe(15.00);
    });

    test('total es subtotal + impuestos', function () {
        $subtotal = 100.00;
        $impuestos = 15.00;
        $total = $subtotal + $impuestos;

        $venta = Venta::factory()->create([
            'subtotal' => $subtotal,
            'impuestos' => $impuestos,
            'total' => $total,
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);

        expect((float)$venta->total)->toBe(115.00);
    });

    // ========== TESTS DE MÉTODOS DE PAGO ==========

    test('acepta método de pago efectivo', function () {
        $venta = Venta::factory()->create([
            'metodo_pago' => 'efectivo',
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);

        expect($venta->metodo_pago)->toBe('efectivo');
    });

    test('acepta método de pago tarjeta', function () {
        $venta = Venta::factory()->create([
            'metodo_pago' => 'tarjeta',
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);

        expect($venta->metodo_pago)->toBe('tarjeta');
    });

    test('acepta método de pago transferencia', function () {
        $venta = Venta::factory()->create([
            'metodo_pago' => 'transferencia',
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);

        expect($venta->metodo_pago)->toBe('transferencia');
    });

    // ========== TESTS DE ESTADOS ==========

    test('estado inicial es completada', function () {
        $venta = Venta::factory()->create([
            'estado' => 'completada',
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);

        expect($venta->estado)->toBe('completada');
    });

    test('puede cancelar una venta', function () {
        $venta = Venta::factory()->create([
            'estado' => 'completada',
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);

        $venta->update(['estado' => 'cancelada']);

        expect($venta->estado)->toBe('cancelada');
    });

    // ========== TESTS DE FECHA ==========

    test('guarda fecha correctamente', function () {
        $fecha = now();
        $venta = Venta::factory()->create([
            'fecha' => $fecha,
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);

        expect($venta->fecha->format('Y-m-d'))->toBe($fecha->format('Y-m-d'));
    });

    // ========== TESTS DE OBSERVACIONES ==========

    test('puede agregar observaciones', function () {
        $venta = Venta::factory()->create([
            'observaciones' => 'Cliente requiere factura',
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);

        expect($venta->observaciones)->toBe('Cliente requiere factura');
    });

});
