<?php

use App\Models\Cliente;

/**
 * Pruebas Unitarias - Modelo Cliente
 * Sistema: Inferno Club - Gestión de Ventas e Inventario
 */

describe('Modelo Cliente', function () {

    // ========== TESTS DE CREACIÓN ==========

    test('puede crear un cliente con todos los campos', function () {
        $cliente = Cliente::create([
            'nombre_completo' => 'Juan Pérez García',
            'identificacion' => '1234567890',
            'email' => 'juan@example.com',
            'telefono' => '0987654321',
            'direccion' => 'Av. Principal 123'
        ]);

        expect($cliente)->not->toBeNull()
            ->and($cliente->nombre_completo)->toBe('Juan Pérez García')
            ->and($cliente->identificacion)->toBe('1234567890');
    });

    test('requiere nombre completo', function () {
        expect(fn() => Cliente::create([
            'identificacion' => '1234567890',
            'email' => 'test@example.com'
        ]))->toThrow(Exception::class);
    });

    test('requiere identificación', function () {
        expect(fn() => Cliente::create([
            'nombre_completo' => 'Juan Pérez',
            'email' => 'test@example.com'
        ]))->toThrow(Exception::class);
    });

    // ========== TESTS DE VALIDACIÓN ==========

    test('identificación debe ser única', function () {
        Cliente::factory()->create([
            'identificacion' => '1234567890'
        ]);

        expect(fn() => Cliente::create([
            'nombre_completo' => 'Otro Cliente',
            'identificacion' => '1234567890',
            'email' => 'otro@example.com',
            'telefono' => '0999999999'
        ]))->toThrow(Exception::class);
    });

    test('email debe ser único si se proporciona', function () {
        Cliente::factory()->create([
            'email' => 'juan@example.com'
        ]);

        expect(fn() => Cliente::create([
            'nombre_completo' => 'María González',
            'identificacion' => '0987654321',
            'email' => 'juan@example.com',
            'telefono' => '0999999999'
        ]))->toThrow(Exception::class);
    });

    test('email puede ser null', function () {
        $cliente = Cliente::create([
            'nombre_completo' => 'Cliente Sin Email',
            'identificacion' => '1111111111',
            'telefono' => '0987654321'
        ]);

        expect($cliente->email)->toBeNull();
    });

    // ========== TESTS DE RELACIONES ==========

    test('puede tener múltiples ventas', function () {
        $cliente = Cliente::factory()->create();
        
        $venta1 = \App\Models\Venta::factory()->create([
            'cliente_id' => $cliente->id
        ]);
        
        $venta2 = \App\Models\Venta::factory()->create([
            'cliente_id' => $cliente->id
        ]);

        expect($cliente->ventas)->toHaveCount(2);
    });

    // ========== TESTS DE FORMATO ==========

    test('telefono se almacena como string', function () {
        $cliente = Cliente::factory()->create([
            'telefono' => '0987654321'
        ]);

        expect($cliente->telefono)->toBeString()
            ->and($cliente->telefono)->toBe('0987654321');
    });

    test('identificación se almacena como string', function () {
        $cliente = Cliente::factory()->create([
            'identificacion' => '1234567890'
        ]);

        expect($cliente->identificacion)->toBeString()
            ->and(strlen($cliente->identificacion))->toBe(10);
    });

    // ========== TESTS DE BÚSQUEDA ==========

    test('puede buscar cliente por identificación', function () {
        Cliente::factory()->create([
            'identificacion' => '9999999999',
            'nombre_completo' => 'Cliente Busqueda'
        ]);

        $cliente = Cliente::where('identificacion', '9999999999')->first();

        expect($cliente)->not->toBeNull()
            ->and($cliente->nombre_completo)->toBe('Cliente Busqueda');
    });

    test('puede buscar cliente por email', function () {
        Cliente::factory()->create([
            'email' => 'buscar@test.com',
            'nombre_completo' => 'Cliente Email'
        ]);

        $cliente = Cliente::where('email', 'buscar@test.com')->first();

        expect($cliente)->not->toBeNull()
            ->and($cliente->nombre_completo)->toBe('Cliente Email');
    });

    // ========== TESTS DE ACTUALIZACIÓN ==========

    test('puede actualizar información del cliente', function () {
        $cliente = Cliente::factory()->create([
            'nombre_completo' => 'Nombre Original',
            'telefono' => '0111111111'
        ]);

        $cliente->update([
            'nombre_completo' => 'Nombre Actualizado',
            'telefono' => '0999999999'
        ]);

        expect($cliente->nombre_completo)->toBe('Nombre Actualizado')
            ->and($cliente->telefono)->toBe('0999999999');
    });

    // ========== TESTS DE DIRECCIÓN ==========

    test('dirección es opcional', function () {
        $cliente = Cliente::factory()->create([
            'nombre_completo' => 'Sin Dirección',
            'identificacion' => '5555555555',
            'email' => 'sindir@test.com',
            'telefono' => '0987654321'
        ]);

        expect($cliente->direccion)->toBeNull();
    });

    test('puede agregar dirección', function () {
        $cliente = Cliente::factory()->create();
        
        $cliente->update([
            'direccion' => 'Calle Nueva 456'
        ]);

        expect($cliente->direccion)->toBe('Calle Nueva 456');
    });

});
