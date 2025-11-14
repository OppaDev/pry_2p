<?php

use App\Models\DetalleVenta;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\User;

/**
 * Pruebas Unitarias - Modelo DetalleVenta
 * Sistema: Inferno Club - Gestión de Ventas e Inventario
 */

describe('Modelo DetalleVenta', function () {

    beforeEach(function () {
        $this->cliente = Cliente::factory()->create();
        $this->vendedor = User::factory()->create();
        $this->producto = Producto::factory()->create([
            'precio' => 45.00,
            'stock_actual' => 20
        ]);
        $this->venta = Venta::factory()->create([
            'cliente_id' => $this->cliente->id,
            'vendedor_id' => $this->vendedor->id
        ]);
    });

    // ========== TESTS DE CREACIÓN ==========

    test('puede crear un detalle de venta', function () {
        $detalle = DetalleVenta::create([
            'venta_id' => $this->venta->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 2,
            'precio_unitario' => 45.00,
            'subtotal_item' => 90.00
        ]);

        expect($detalle)->not->toBeNull()
            ->and($detalle->cantidad)->toBe(2)
            ->and($detalle->precio_unitario)->toBe('45.00');
    });

    // ========== TESTS DE CÁLCULOS AUTOMÁTICOS ==========

    test('calcula subtotal_item automáticamente al crear', function () {
        $detalle = DetalleVenta::create([
            'venta_id' => $this->venta->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 3,
            'precio_unitario' => 25.00
            // No pasamos subtotal_item
        ]);

        expect((float)$detalle->subtotal_item)->toBe(75.00);
    });

    test('recalcula subtotal_item al actualizar cantidad', function () {
        $detalle = DetalleVenta::create([
            'venta_id' => $this->venta->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 2,
            'precio_unitario' => 30.00,
            'subtotal_item' => 60.00
        ]);

        $detalle->update(['cantidad' => 5]);

        expect((float)$detalle->subtotal_item)->toBe(150.00);
    });

    test('recalcula subtotal_item al actualizar precio_unitario', function () {
        $detalle = DetalleVenta::create([
            'venta_id' => $this->venta->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 4,
            'precio_unitario' => 20.00,
            'subtotal_item' => 80.00
        ]);

        $detalle->update(['precio_unitario' => 25.00]);

        expect((float)$detalle->subtotal_item)->toBe(100.00);
    });

    test('método calcularSubtotalItem funciona correctamente', function () {
        $detalle = new DetalleVenta([
            'cantidad' => 5,
            'precio_unitario' => 12.50
        ]);

        $subtotal = $detalle->calcularSubtotalItem();

        expect($subtotal)->toBe(62.5);
    });

    // ========== TESTS DE RELACIONES ==========

    test('tiene relación con venta', function () {
        $detalle = DetalleVenta::create([
            'venta_id' => $this->venta->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 1,
            'precio_unitario' => 45.00,
            'subtotal_item' => 45.00
        ]);

        expect($detalle->venta)->toBeInstanceOf(Venta::class)
            ->and($detalle->venta->id)->toBe($this->venta->id);
    });

    test('tiene relación con producto', function () {
        $detalle = DetalleVenta::create([
            'venta_id' => $this->venta->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 1,
            'precio_unitario' => 45.00,
            'subtotal_item' => 45.00
        ]);

        expect($detalle->producto)->toBeInstanceOf(Producto::class)
            ->and($detalle->producto->id)->toBe($this->producto->id);
    });

    // ========== TESTS DE VALIDACIÓN ==========

    test('cantidad debe ser mayor a 0', function () {
        $detalle = DetalleVenta::make([
            'venta_id' => $this->venta->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 0,
            'precio_unitario' => 45.00
        ]);

        expect($detalle->cantidad)->toBeGreaterThan(0);
    })->skip('Necesita validación en el modelo');

    test('precio_unitario debe ser mayor a 0', function () {
        $detalle = DetalleVenta::make([
            'venta_id' => $this->venta->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 1,
            'precio_unitario' => 0
        ]);

        expect($detalle->precio_unitario)->toBeGreaterThan(0);
    })->skip('Necesita validación en el modelo');

    // ========== TESTS DE TIPOS DE DATOS ==========

    test('cantidad es entero', function () {
        $detalle = DetalleVenta::create([
            'venta_id' => $this->venta->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 5,
            'precio_unitario' => 45.00,
            'subtotal_item' => 225.00
        ]);

        expect($detalle->cantidad)->toBeInt();
    });

    test('precio_unitario es decimal', function () {
        $detalle = DetalleVenta::create([
            'venta_id' => $this->venta->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 1,
            'precio_unitario' => 45.99,
            'subtotal_item' => 45.99
        ]);

        expect($detalle->precio_unitario)->toBeString()
            ->and(number_format((float)$detalle->precio_unitario, 2))->toBe('45.99');
    });

    test('subtotal_item es decimal', function () {
        $detalle = DetalleVenta::create([
            'venta_id' => $this->venta->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 3,
            'precio_unitario' => 15.50,
            'subtotal_item' => 46.50
        ]);

        expect($detalle->subtotal_item)->toBeString()
            ->and(number_format((float)$detalle->subtotal_item, 2))->toBe('46.50');
    });

    // ========== TESTS DE MÚLTIPLES DETALLES ==========

    test('una venta puede tener múltiples detalles', function () {
        $producto2 = Producto::factory()->create(['precio' => 30.00]);
        $producto3 = Producto::factory()->create(['precio' => 20.00]);

        DetalleVenta::create([
            'venta_id' => $this->venta->id,
            'producto_id' => $this->producto->id,
            'cantidad' => 2,
            'precio_unitario' => 45.00,
            'subtotal_item' => 90.00
        ]);

        DetalleVenta::create([
            'venta_id' => $this->venta->id,
            'producto_id' => $producto2->id,
            'cantidad' => 1,
            'precio_unitario' => 30.00,
            'subtotal_item' => 30.00
        ]);

        DetalleVenta::create([
            'venta_id' => $this->venta->id,
            'producto_id' => $producto3->id,
            'cantidad' => 3,
            'precio_unitario' => 20.00,
            'subtotal_item' => 60.00
        ]);

        $detalles = DetalleVenta::where('venta_id', $this->venta->id)->get();

        expect($detalles)->toHaveCount(3);
    });

});
