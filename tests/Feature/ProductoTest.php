<?php

use App\Models\Producto;
use App\Models\Categoria;

/**
 * Pruebas Unitarias - Modelo Producto
 * Sistema: Inferno Club - Gestión de Ventas e Inventario
 */

describe('Modelo Producto', function () {
    
    beforeEach(function () {
        // Crear categoría de prueba
        $this->categoria = Categoria::factory()->create([
            'nombre' => 'Whisky',
            'descripcion' => 'Whisky de prueba'
        ]);
    });

    // ========== TESTS DE CREACIÓN ==========
    
    test('puede crear un producto con todos los campos', function () {
        $producto = Producto::create([
            'codigo' => 'TEST001',
            'nombre' => 'Johnnie Walker Black Label',
            'marca' => 'Johnnie Walker',
            'presentacion' => 'Botella',
            'capacidad' => '750ml',
            'volumen_ml' => 750,
            'precio' => 45.99,
            'stock_actual' => 20,
            'stock_minimo' => 5,
            'categoria_id' => $this->categoria->id,
            'descripcion' => 'Whisky escocés premium'
        ]);

        expect($producto)->not->toBeNull()
            ->and($producto->codigo)->toBe('TEST001')
            ->and($producto->precio)->toBe('45.99')
            ->and($producto->stock_actual)->toBe(20);
    });

    test('requiere campos obligatorios', function () {
        expect(fn() => Producto::create([]))
            ->toThrow(Exception::class);
    });

    test('el precio es un decimal con 2 decimales', function () {
        $producto = Producto::factory()->create([
            'precio' => 29.99,
            'categoria_id' => $this->categoria->id
        ]);

        expect($producto->precio)
            ->toBeFloat()
            ->and(number_format($producto->precio, 2))->toBe('29.99');
    });

    // ========== TESTS DE RELACIONES ==========

    test('tiene relación con categoría', function () {
        $producto = Producto::factory()->create([
            'categoria_id' => $this->categoria->id
        ]);

        expect($producto->categoria)->toBeInstanceOf(Categoria::class)
            ->and($producto->categoria->nombre)->toBe('Whisky');
    });

    // ========== TESTS DE STOCK ==========

    test('detecta stock bajo correctamente', function () {
        $productoBajoStock = Producto::factory()->create([
            'stock_actual' => 3,
            'stock_minimo' => 5,
            'categoria_id' => $this->categoria->id
        ]);

        $productoStockOk = Producto::factory()->create([
            'stock_actual' => 10,
            'stock_minimo' => 5,
            'categoria_id' => $this->categoria->id
        ]);

        expect($productoBajoStock->stock_actual < $productoBajoStock->stock_minimo)->toBeTrue()
            ->and($productoStockOk->stock_actual >= $productoStockOk->stock_minimo)->toBeTrue();
    });

    test('stock actual no puede ser negativo', function () {
        $producto = Producto::factory()->create([
            'stock_actual' => 5,
            'categoria_id' => $this->categoria->id
        ]);

        $producto->stock_actual = -1;
        $producto->save();

        // Recargar del DB
        $producto->refresh();
        
        expect($producto->stock_actual)->toBeGreaterThanOrEqual(0);
    })->skip('Necesita validación en el modelo');

    test('puede actualizar stock', function () {
        $producto = Producto::factory()->create([
            'stock_actual' => 10,
            'categoria_id' => $this->categoria->id
        ]);

        $producto->update(['stock_actual' => 15]);

        expect($producto->stock_actual)->toBe(15);
    });

    // ========== TESTS DE SOFT DELETES ==========

    test('soft delete funciona correctamente', function () {
        $producto = Producto::factory()->create([
            'categoria_id' => $this->categoria->id
        ]);

        $productoId = $producto->id;
        $producto->delete();

        // No debe estar en consultas normales
        expect(Producto::find($productoId))->toBeNull();

        // Debe estar en withTrashed
        expect(Producto::withTrashed()->find($productoId))->not->toBeNull()
            ->and(Producto::withTrashed()->find($productoId)->deleted_at)->not->toBeNull();
    });

    test('puede restaurar producto eliminado', function () {
        $producto = Producto::factory()->create([
            'categoria_id' => $this->categoria->id
        ]);

        $producto->delete();
        $producto->restore();

        expect($producto->deleted_at)->toBeNull()
            ->and(Producto::find($producto->id))->not->toBeNull();
    });

    // ========== TESTS DE BÚSQUEDA Y FILTRADO ==========

    test('puede buscar productos por código', function () {
        Producto::factory()->create([
            'codigo' => 'ABC123',
            'categoria_id' => $this->categoria->id
        ]);

        $producto = Producto::where('codigo', 'ABC123')->first();

        expect($producto)->not->toBeNull()
            ->and($producto->codigo)->toBe('ABC123');
    });

    test('puede filtrar productos por categoría', function () {
        Producto::factory()->count(3)->create([
            'categoria_id' => $this->categoria->id
        ]);

        $otraCategoria = Categoria::factory()->create(['nombre' => 'Ron']);
        Producto::factory()->count(2)->create([
            'categoria_id' => $otraCategoria->id
        ]);

        $productosWhisky = Producto::where('categoria_id', $this->categoria->id)->get();

        expect($productosWhisky)->toHaveCount(3);
    });

    // ========== TESTS DE VALIDACIÓN ==========

    test('código debe ser único', function () {
        Producto::factory()->create([
            'codigo' => 'UNIQUE001',
            'categoria_id' => $this->categoria->id
        ]);

        expect(fn() => Producto::create([
            'codigo' => 'UNIQUE001',
            'nombre' => 'Otro producto',
            'precio' => 10.00,
            'stock_actual' => 1,
            'stock_minimo' => 1,
            'categoria_id' => $this->categoria->id
        ]))->toThrow(Exception::class);
    });

    test('precio debe ser mayor a 0', function () {
        $producto = Producto::factory()->make([
            'precio' => 0,
            'categoria_id' => $this->categoria->id
        ]);

        expect($producto->precio)->toBeGreaterThan(0);
    })->skip('Necesita validación en el modelo');

    // ========== TESTS DE FORMATO ==========

    test('capacidad y presentación se almacenan correctamente', function () {
        $producto = Producto::factory()->create([
            'presentacion' => 'Botella',
            'capacidad' => '750ml',
            'volumen_ml' => 750,
            'categoria_id' => $this->categoria->id
        ]);

        expect($producto->presentacion)->toBe('Botella')
            ->and($producto->capacidad)->toBe('750ml')
            ->and($producto->volumen_ml)->toBe(750);
    });

});
