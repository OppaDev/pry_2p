# 🧪 GUÍA DE PRUEBAS UNITARIAS - INFERNO CLUB

## 📋 Índice
1. [Introducción](#introducción)
2. [Configuración](#configuración)
3. [Estructura de Pruebas](#estructura-de-pruebas)
4. [Ejecutar Pruebas](#ejecutar-pruebas)
5. [Ejemplos](#ejemplos)

---

## 🎯 Introducción

Este proyecto utiliza **Pest PHP** como framework de testing. Las pruebas están organizadas en:
- **Unit Tests**: Pruebas de lógica de negocio aisladas (sin DB)
- **Feature Tests**: Pruebas de integración con base de datos

## 🔧 Configuración

### 1. Instalar Dependencias
```bash
composer install
```

### 2. Configurar Base de Datos de Pruebas

Editar `phpunit.xml`:
```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

### 3. Verificar Instalación
```bash
php artisan test
```

---

## 📁 Estructura de Pruebas

```
tests/
├── Feature/           # Pruebas de integración (con DB)
│   ├── ProductoTest.php
│   ├── VentaTest.php
│   ├── ClienteTest.php
│   └── DetalleVentaTest.php
├── Unit/              # Pruebas unitarias puras (sin DB)
│   └── Services/
└── Pest.php           # Configuración global de Pest
```

---

## 🚀 Ejecutar Pruebas

### Todos los Tests
```bash
php artisan test
```

### Solo Feature Tests
```bash
php artisan test --testsuite=Feature
```

### Solo Unit Tests
```bash
php artisan test --testsuite=Unit
```

### Test Específico
```bash
php artisan test tests/Feature/ProductoTest.php
```

### Con Cobertura
```bash
php artisan test --coverage
```

### En Paralelo (más rápido)
```bash
php artisan test --parallel
```

### Modo Verbose
```bash
php artisan test -vvv
```

---

## 📊 Resumen de Pruebas Creadas

| Archivo | Tests | Cobertura |
|---------|-------|-----------|
| **ProductoTest.php** | 17 tests | CRUD, relaciones, soft deletes, stock |
| **VentaTest.php** | 16 tests | Creación, cálculos, métodos de pago, estados |
| **DetalleVentaTest.php** | 14 tests | Cálculos automáticos, relaciones, validaciones |
| **ClienteTest.php** | 13 tests | CRUD, validaciones, búsquedas |
| **TOTAL** | **60 tests** | Modelos principales del sistema |

---

## 🎓 Ejemplos de Pruebas

### Prueba Básica

```php
test('puede crear un producto', function () {
    $producto = Producto::factory()->create();
    
    expect($producto)->not->toBeNull()
        ->and($producto->nombre)->toBeString();
});
```

### Prueba con Relaciones

```php
test('producto tiene categoría', function () {
    $categoria = Categoria::factory()->create();
    $producto = Producto::factory()->create([
        'categoria_id' => $categoria->id
    ]);
    
    expect($producto->categoria)->toBeInstanceOf(Categoria::class);
});
```

### Prueba de Validación

```php
test('requiere campos obligatorios', function () {
    expect(fn() => Producto::create([]))
        ->toThrow(Exception::class);
});
```

### Prueba de Cálculos

```php
test('calcula subtotal correctamente', function () {
    $detalle = DetalleVenta::create([
        'cantidad' => 3,
        'precio_unitario' => 25.00
    ]);
    
    expect((float)$detalle->subtotal_item)->toBe(75.00);
});
```

---

## 📈 Cobertura de Código

### Generar Reporte de Cobertura

```bash
php artisan test --coverage --min=80
```

### Ver Cobertura HTML

```bash
XDEBUG_MODE=coverage php artisan test --coverage-html coverage/
```

Abrir: `coverage/index.html`

---

## 🐛 Debugging de Pruebas

### Ver Output de Tests
```bash
php artisan test --debug
```

### Ejecutar con dd() habilitado
```php
test('debug example', function () {
    $producto = Producto::factory()->create();
    dd($producto->toArray()); // Se mostrará en consola
});
```

### Ver Queries Ejecutadas
```php
test('ver queries', function () {
    DB::enableQueryLog();
    
    $productos = Producto::all();
    
    dump(DB::getQueryLog());
    
    expect($productos)->not->toBeEmpty();
});
```

---

## ✅ Mejores Prácticas

### 1. Nombres Descriptivos
```php
// ❌ Malo
test('test1', function () { ... });

// ✅ Bueno
test('puede crear un producto con todos los campos', function () { ... });
```

### 2. Usar Arrange-Act-Assert
```php
test('calcula total correctamente', function () {
    // Arrange (Preparar)
    $subtotal = 100.00;
    $impuestos = 15.00;
    
    // Act (Actuar)
    $venta = Venta::factory()->create([
        'subtotal' => $subtotal,
        'impuestos' => $impuestos
    ]);
    
    // Assert (Verificar)
    expect((float)$venta->total)->toBe(115.00);
});
```

### 3. Agrupar Tests Relacionados
```php
describe('Modelo Producto', function () {
    describe('Creación', function () {
        test('puede crear producto', function () { ... });
        test('requiere campos obligatorios', function () { ... });
    });
    
    describe('Stock', function () {
        test('detecta stock bajo', function () { ... });
        test('puede actualizar stock', function () { ... });
    });
});
```

### 4. Usar beforeEach para Setup Común
```php
beforeEach(function () {
    $this->categoria = Categoria::factory()->create();
});

test('usa categoria del setup', function () {
    $producto = Producto::factory()->create([
        'categoria_id' => $this->categoria->id
    ]);
    
    expect($producto->categoria_id)->toBe($this->categoria->id);
});
```

### 5. Marcar Tests Pendientes
```php
test('debe validar edad del cliente', function () {
    // TODO: Implementar
})->skip('Pendiente de implementar');
```

---

## 📝 Comandos Útiles de Pest

| Comando | Descripción |
|---------|-------------|
| `php artisan test --filter="nombre"` | Ejecutar tests por nombre |
| `php artisan test --bail` | Detenerse en el primer fallo |
| `php artisan test --stop-on-failure` | Igual que --bail |
| `php artisan test --group=integration` | Ejecutar grupo específico |
| `php artisan test --exclude-group=slow` | Excluir grupo |
| `php artisan test --testdox` | Formato de documentación |

---

## 🎯 Tests por Funcionalidad

### Tests de Productos
```bash
# 17 pruebas cubren:
- Creación de productos
- Validación de campos
- Relaciones con categorías
- Gestión de stock
- Soft deletes
- Búsquedas y filtros
- Validación de unicidad
```

### Tests de Ventas
```bash
# 16 pruebas cubren:
- Creación de ventas
- Cálculo de totales e IVA
- Métodos de pago
- Estados de venta
- Relaciones con clientes y vendedores
- Observaciones
```

### Tests de Detalles de Venta
```bash
# 14 pruebas cubren:
- Cálculo automático de subtotales
- Relaciones con ventas y productos
- Validación de tipos de datos
- Múltiples detalles por venta
```

### Tests de Clientes
```bash
# 13 pruebas cubren:
- CRUD completo
- Validación de unicidad
- Campos opcionales
- Búsquedas
- Actualización de datos
```

---

## 🚨 Troubleshooting

### Error: "could not find driver"
**Solución**: Instalar extensión SQLite
```bash
# Windows
# Descomentar en php.ini:
extension=pdo_sqlite
extension=sqlite3
```

### Error: "Class not found"
**Solución**: Regenerar autoload
```bash
composer dump-autoload
```

### Tests muy lentos
**Solución**: Usar SQLite en memoria
```xml
<!-- phpunit.xml -->
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

### Fallo en factory
**Solución**: Verificar que exista el factory
```bash
php artisan make:factory ProductoFactory --model=Producto
```

---

## 📚 Recursos

- [Pest PHP Docs](https://pestphp.com/)
- [Laravel Testing](https://laravel.com/docs/testing)
- [Expectation API](https://pestphp.com/docs/expectations)
- [Plugins de Pest](https://pestphp.com/docs/plugins)

---

## 🎉 Próximos Pasos

1. ✅ Crear tests para Categorías
2. ✅ Crear tests para Facturación
3. ✅ Crear tests para Auditorías
4. ✅ Implementar CI/CD con tests automáticos
5. ✅ Aumentar cobertura al 80%+

---

**¡Tus tests están listos! Ejecuta `php artisan test` para verlos en acción.** 🔥
