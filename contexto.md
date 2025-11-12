# Contexto del Proyecto - pry_2p

> **Documento de análisis completo del proyecto Laravel**  
> **Fecha de análisis:** 2 de octubre de 2025  
> **Versión del framework:** Laravel 12.0  
> **Repositorio:** OppaDev/pry_2p (branch: main)

---

## 📋 Tabla de Contenidos

1. [Descripción General](#descripción-general)
2. [Stack Tecnológico](#stack-tecnológico)
3. [Arquitectura del Proyecto](#arquitectura-del-proyecto)
4. [Modelos y Base de Datos](#modelos-y-base-de-datos)
5. [Controladores y Rutas](#controladores-y-rutas)
6. [Sistema de Auditoría](#sistema-de-auditoría)
7. [Sistema de Autenticación](#sistema-de-autenticación)
8. [Frontend y UI](#frontend-y-ui)
9. [Validaciones y Form Requests](#validaciones-y-form-requests)
10. [Configuración](#configuración)
11. [Características Principales](#características-principales)
12. [Flujos de Trabajo](#flujos-de-trabajo)
13. [Comandos Útiles](#comandos-útiles)

---

## 📖 Descripción General

Este es un proyecto Laravel 12 que implementa un **sistema de gestión con auditoría completa**. El sistema permite administrar usuarios y productos con capacidades de soft delete, restauración y seguimiento completo de cambios mediante un sistema de auditoría robusto.

### Propósito del Proyecto
- Gestión integral de usuarios con roles y permisos
- Gestión de inventario de productos
- Trazabilidad completa de todas las operaciones (auditoría)
- Sistema de autenticación seguro con Laravel Breeze
- Dashboard administrativo con estadísticas

---

## 🛠 Stack Tecnológico

### Backend
| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| **PHP** | ^8.2 | Lenguaje base |
| **Laravel Framework** | ^12.0 | Framework principal |
| **PostgreSQL** | - | Base de datos principal |
| **Laravel Breeze** | ^2.3 | Scaffolding de autenticación |
| **Laravel Tinker** | ^2.10.1 | REPL interactivo |

### Paquetes Principales
| Paquete | Versión | Funcionalidad |
|---------|---------|---------------|
| **owen-it/laravel-auditing** | ^14.0 | Sistema de auditoría completo |
| **spatie/laravel-permission** | ^6.19 | Gestión de roles y permisos |
| **nesbot/carbon** | ^3.10 | Manipulación de fechas |

### Frontend
| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| **Vite** | ^6.2.4 | Build tool y HMR |
| **TailwindCSS** | ^3.1.0 | Framework CSS utility-first |
| **Alpine.js** | ^3.4.2 | Framework JavaScript reactivo |
| **Chart.js** | ^4.5.0 | Gráficos y visualizaciones |
| **Axios** | ^1.8.2 | Cliente HTTP |
| **Perfect Scrollbar** | ^1.5.6 | Scrollbars personalizados |
| **Highlight.js** | ^11.4.0 | Resaltado de sintaxis |

### Herramientas de Desarrollo
- **Pest PHP** (^3.8): Framework de testing
- **Laravel Pail** (^1.2.2): Visualización de logs
- **Laravel Pint** (^1.13): Code styling
- **Laravel Sail** (^1.41): Entorno Docker

---

## 🏗 Arquitectura del Proyecto

### Estructura de Directorios Principal

```
pry_2p/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/              # Controladores de autenticación
│   │   │   ├── AuditController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ProductoController.php
│   │   │   ├── ProfileController.php
│   │   │   └── UserController.php
│   │   └── Requests/
│   │       ├── ValidarEditProducto.php
│   │       ├── ValidarEditUser.php
│   │       ├── ValidarStoreProducto.php
│   │       └── ValidarStoreUser.php
│   ├── Models/
│   │   ├── Producto.php
│   │   └── User.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── View/
│       └── Components/
├── bootstrap/
│   ├── app.php                    # Bootstrap de la aplicación
│   └── providers.php
├── config/                        # Archivos de configuración
│   ├── app.php
│   ├── audit.php                  # Configuración de auditoría
│   ├── auth.php
│   ├── database.php
│   └── permission.php             # Configuración de permisos
├── database/
│   ├── migrations/                # Migraciones de BD
│   ├── factories/
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── DashboardSeeder.php
├── public/                        # Archivos públicos
├── resources/
│   ├── css/
│   │   ├── app.css
│   │   ├── modal-styles.css
│   │   └── soft-ui/
│   ├── js/
│   │   ├── app.js
│   │   ├── bootstrap.js
│   │   ├── modal-manager.js
│   │   └── soft-ui/
│   └── views/
│       ├── audits/
│       ├── auth/
│       ├── layouts/
│       ├── productos/
│       ├── profile/
│       ├── users/
│       └── dashboard.blade.php
├── routes/
│   ├── auth.php                   # Rutas de autenticación
│   ├── console.php
│   └── web.php                    # Rutas web principales
├── storage/
├── tests/
│   ├── Feature/
│   ├── Unit/
│   └── Pest.php
└── vendor/
```

---

## 🗄 Modelos y Base de Datos

### Modelo: User

**Ubicación:** `app/Models/User.php`

#### Características
- Extiende `Authenticatable`
- Implementa `Auditable` para registro de cambios
- Usa `SoftDeletes` para eliminación lógica
- Integra `HasRoles` de Spatie Permission

#### Atributos Principales
```php
protected $fillable = ['name', 'email', 'password'];
protected $hidden = ['password', 'remember_token'];
```

#### Auditoría
```php
protected $auditInclude = ['name', 'email', 'email_verified_at'];
protected $auditExclude = ['password', 'remember_token'];
protected $auditEvents = ['created', 'updated', 'deleted', 'restored'];
```

#### Métodos Especiales
- `generateTags()`: Genera etiquetas para auditoría con motivos
- `transformAudit()`: Transforma datos de auditoría agregando motivos en formato JSON

---

### Modelo: Producto

**Ubicación:** `app/Models/Producto.php`

#### Características
- Extiende `Model`
- Implementa `Auditable`
- Usa `SoftDeletes`

#### Atributos
```php
protected $fillable = ['nombre', 'codigo', 'cantidad', 'precio'];
```

#### Campos de Base de Datos
| Campo | Tipo | Descripción | Restricciones |
|-------|------|-------------|---------------|
| id | bigint | ID autoincremental | PK |
| nombre | string | Nombre del producto | nullable, max:255 |
| codigo | string | Código único | unique, max:10 |
| cantidad | integer | Stock disponible | min:0, max:200 |
| precio | decimal(10,2) | Precio unitario | min:0, 2 decimales |
| created_at | timestamp | Fecha creación | - |
| updated_at | timestamp | Fecha actualización | - |
| deleted_at | timestamp | Soft delete | nullable |

#### Auditoría
```php
protected $auditInclude = ['nombre', 'codigo', 'cantidad', 'precio'];
protected $auditEvents = ['created', 'updated', 'deleted', 'restored'];
```

---

### Migraciones Principales

#### 1. `create_users_table` (0001_01_01_000000)
```sql
- users (id, name, email, email_verified_at, password, remember_token, timestamps, deleted_at)
- password_reset_tokens (email, token, created_at)
- sessions (id, user_id, ip_address, user_agent, payload, last_activity)
```

#### 2. `create_productos_table` (2025_06_17_163403)
```sql
- productos (id, nombre, codigo, cantidad, precio, timestamps, deleted_at)
```

#### 3. `create_permission_tables` (2025_06_03_131427)
Tablas de Spatie Permission:
- permissions
- roles
- model_has_permissions
- model_has_roles
- role_has_permissions

#### 4. `create_audits_table` (2025_06_28_203255)
Tabla central de auditoría del paquete owen-it/laravel-auditing

#### 5. `modify_audits_tags_to_json` (2025_07_01_051608)
Modifica el campo `tags` a tipo JSON para PostgreSQL

---

### Base de Datos: PostgreSQL

#### Configuración
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pry_conjunta
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🎮 Controladores y Rutas

### DashboardController

**Ubicación:** `app/Http/Controllers/DashboardController.php`

#### Método: `index()`
Muestra el dashboard principal con estadísticas:
- Total de usuarios
- Total de productos
- Total de stock
- Valor total del inventario

**Ruta:** `GET /dashboard`  
**Middleware:** `auth`, `verified`  
**Vista:** `dashboard.blade.php`

---

### UserController

**Ubicación:** `app/Http/Controllers/UserController.php`

#### Operaciones CRUD Completas

| Método | Ruta | Acción | Descripción |
|--------|------|--------|-------------|
| index | GET /users | Listar | Paginación, búsqueda, filtros |
| create | GET /users/create | Formulario | Mostrar formulario de creación |
| store | POST /users | Crear | Validación con ValidarStoreUser |
| show | GET /users/{user} | Ver | Detalles + historial de auditoría |
| edit | GET /users/{user}/edit | Editar form | Formulario de edición |
| update | PATCH /users/{user} | Actualizar | Validación con ValidarEditUser |
| destroy | DELETE /users/{user} | Soft delete | Requiere motivo + password |

#### Rutas Adicionales

| Ruta | Método | Función | Descripción |
|------|--------|---------|-------------|
| /users/{user}/audit-history | GET | auditHistory() | Historial completo de cambios |
| /usuarios-eliminados | GET | deletedUsers() | Lista de usuarios eliminados |
| /users/{id}/restore | PATCH | restore() | Restaurar usuario eliminado |
| /users/{id}/force-delete | DELETE | forceDelete() | Eliminación permanente |

#### Características de Seguridad
1. **Validación de contraseña** en operaciones destructivas
2. **Verificación de usuario actual** (no puede eliminarse a sí mismo)
3. **Registro de auditoría manual** en force delete
4. **Logging detallado** de operaciones críticas
5. **Transacciones de BD** para integridad

---

### ProductoController

**Ubicación:** `app/Http/Controllers/ProductoController.php`

#### Operaciones CRUD Completas

| Método | Ruta | Acción | Descripción |
|--------|------|--------|-------------|
| index | GET /productos | Listar | Paginación y búsqueda |
| create | GET /productos/create | Formulario | Crear producto |
| store | POST /productos | Crear | Con validación |
| show | GET /productos/{producto} | Ver | Detalles + auditoría |
| edit | GET /productos/{producto}/edit | Editar | Formulario edición |
| update | PATCH /productos/{producto} | Actualizar | Actualización validada |
| destroy | DELETE /productos/{producto} | Soft delete | Con motivo |

#### Rutas Adicionales

| Ruta | Método | Función | Descripción |
|------|--------|---------|-------------|
| /productos/{producto}/audit-history | GET | auditHistory() | Historial de cambios |
| /productos-eliminados | GET | deletedProducts() | Productos eliminados |
| /productos/{id}/restore | PATCH | restore() | Restaurar producto |
| /productos/{id}/force-delete | DELETE | forceDelete() | Eliminar permanentemente |

#### Lógica de Negocio Especial
```php
// En store(): Agrega sufijo aleatorio al código
$producto->nombre = $request->nombre;
$producto->save();

// En update(): Modifica el código con random
$producto->codigo = $producto->codigo . rand(100, 999);
$producto->save();
```

---

### AuditController

**Ubicación:** `app/Http/Controllers/AuditController.php`

#### Método: `auditsByUser()`
Vista consolidada de todas las auditorías del sistema

**Características:**
- Paginación configurable (5, 10, 15, 25, 50)
- Búsqueda por nombre, email, código, nombre de producto
- Filtros por tipo de evento (created, updated, deleted, restored, force_deleted)
- Filtros por tipo de modelo (User, Producto)
- Join con tablas relacionadas para mostrar información contextual

**Estadísticas incluidas:**
```php
$stats = [
    'total_audits' => Total de registros de auditoría,
    'total_users_with_audits' => Usuarios que han realizado cambios,
    'events_count' => Conteo por tipo de evento,
    'recent_activity' => Actividad de últimos 7 días
];
```

**Ruta:** `GET /auditorias`  
**Vista:** `audits/by-user.blade.php`

#### Método: `show()`
Muestra detalles de un registro de auditoría específico

**Características:**
- Carga el usuario que realizó el cambio
- Intenta cargar el modelo auditado (incluso si está eliminado)
- Maneja modelos eliminados con `withTrashed()`

**Ruta:** `GET /auditorias/{audit}`  
**Vista:** `audits/show.blade.php`

---

### ProfileController

**Ubicación:** Laravel Breeze (estándar)

Gestiona el perfil del usuario autenticado:
- Editar información personal
- Actualizar email
- Cambiar contraseña
- Eliminar cuenta

---

### Controladores de Autenticación

**Ubicación:** `app/Http/Controllers/Auth/`

Laravel Breeze proporciona los siguientes controladores:

| Controlador | Función |
|-------------|---------|
| AuthenticatedSessionController | Login/Logout |
| RegisteredUserController | Registro de nuevos usuarios |
| PasswordResetLinkController | Solicitar reset de contraseña |
| NewPasswordController | Establecer nueva contraseña |
| EmailVerificationPromptController | Verificación de email |
| VerifyEmailController | Confirmar verificación |
| ConfirmablePasswordController | Confirmar contraseña |
| PasswordController | Actualizar contraseña |

---

## 🔍 Sistema de Auditoría

### Paquete: owen-it/laravel-auditing

**Versión:** ^14.0

### Configuración

**Archivo:** `config/audit.php`

```php
'enabled' => env('AUDITING_ENABLED', true),
'implementation' => OwenIt\Auditing\Models\Audit::class,

'user' => [
    'morph_prefix' => 'user',
    'guards' => ['web', 'api'],
    'resolver' => OwenIt\Auditing\Resolvers\UserResolver::class,
],

'resolvers' => [
    'ip_address' => OwenIt\Auditing\Resolvers\IpAddressResolver::class,
    'user_agent' => OwenIt\Auditing\Resolvers\UserAgentResolver::class,
    'url' => OwenIt\Auditing\Resolvers\UrlResolver::class,
],
```

### Eventos Auditados

| Evento | User | Producto | Descripción |
|--------|------|----------|-------------|
| **created** | ✅ | ✅ | Creación de registro |
| **updated** | ✅ | ✅ | Actualización de datos |
| **deleted** | ✅ | ✅ | Soft delete |
| **restored** | ✅ | ✅ | Restauración |
| **force_deleted** | Manual | Manual | Eliminación permanente |

### Estructura de Registro de Auditoría

Tabla: `audits`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | ID único |
| user_type | string | Clase del usuario (User) |
| user_id | bigint | ID del usuario que hizo el cambio |
| event | string | Tipo de evento |
| auditable_type | string | Clase del modelo auditado |
| auditable_id | bigint | ID del registro auditado |
| old_values | json | Valores anteriores |
| new_values | json | Valores nuevos |
| url | string | URL de la petición |
| ip_address | string | IP del usuario |
| user_agent | text | User agent del navegador |
| tags | json | Etiquetas personalizadas |
| created_at | timestamp | Fecha del cambio |
| updated_at | timestamp | Fecha de actualización |

### Sistema de Tags Personalizado

Ambos modelos (User y Producto) implementan un sistema de tags para registrar motivos:

```php
public function generateTags(): array
{
    $tags = [];
    if ($this->auditComment) {
        $tags[] = 'motivo:' . $this->auditComment;
    }
    return $tags;
}

public function transformAudit(array $data): array
{
    if ($this->auditComment) {
        $currentTags = $data['tags'] ?? [];
        if (is_string($currentTags)) {
            $currentTags = json_decode($currentTags, true) ?? [];
        }
        if (!is_array($currentTags)) {
            $currentTags = [];
        }
        $currentTags[] = 'motivo:' . $this->auditComment;
        $data['tags'] = json_encode($currentTags);
    }
    return $data;
}
```

### Uso en Controladores

```php
// Ejemplo: Soft delete con motivo
$producto->auditComment = $request->motivo;
$producto->delete();

// Ejemplo: Restauración con motivo
$user->auditComment = $request->motivo;
$user->restore();

// Ejemplo: Force delete con auditoría manual
\OwenIt\Auditing\Models\Audit::create([
    'user_type' => get_class(Auth::user()),
    'user_id' => Auth::id(),
    'event' => 'force_deleted',
    'auditable_type' => get_class($producto),
    'auditable_id' => $producto->id,
    'old_values' => $producto->toArray(),
    'new_values' => [],
    'url' => $request->url(),
    'ip_address' => $request->ip(),
    'user_agent' => $request->userAgent(),
    'tags' => json_encode([
        'motivo:' . $request->motivo,
        'accion:eliminacion_permanente',
        'password_verificada:true'
    ]),
]);
```

---

## 🔐 Sistema de Autenticación

### Laravel Breeze

**Versión:** ^2.3  
**Stack:** Blade + Alpine.js + Tailwind

### Rutas de Autenticación

**Archivo:** `routes/auth.php`

#### Rutas Públicas (guest)
- `GET /register` - Formulario de registro
- `POST /register` - Procesar registro
- `GET /login` - Formulario de login
- `POST /login` - Procesar login
- `GET /forgot-password` - Solicitar reset
- `POST /forgot-password` - Enviar email de reset
- `GET /reset-password/{token}` - Formulario de reset
- `POST /reset-password` - Procesar reset

#### Rutas Protegidas (auth)
- `GET /verify-email` - Aviso de verificación
- `GET /verify-email/{id}/{hash}` - Verificar email (signed, throttle)
- `POST /email/verification-notification` - Reenviar email
- `GET /confirm-password` - Confirmar contraseña
- `POST /confirm-password` - Procesar confirmación
- `PUT /password` - Actualizar contraseña
- `POST /logout` - Cerrar sesión

### Middleware

**Definidos en:** `bootstrap/app.php`

```php
$middleware->alias([
    'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
    'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
    'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
]);
```

### Configuración de Sesiones

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
```

### Sistema de Roles y Permisos

**Paquete:** spatie/laravel-permission v6.19

**Configuración:** `config/permission.php`

**Modelos:**
- Permission
- Role

**Traits:**
- `HasRoles` (usado en User model)

---

## 🎨 Frontend y UI

### Framework CSS: Soft UI Dashboard

Implementación personalizada basada en Soft UI Dashboard con TailwindCSS.

### Configuración de Tailwind

**Archivo:** `tailwind.config.js`

#### Características Principales
- **JIT Mode** habilitado
- **Dark mode:** class-based
- Paleta de colores extendida (20+ colores personalizados)
- Sistema de espaciado personalizado (px a rem)
- Fuente: Open Sans + Roboto
- Animaciones personalizadas
- Sombras soft personalizadas
- Gradientes predefinidos

#### Colores Principales
```javascript
slate: { 700: "#344767" }  // Color principal de texto
gray: { 50: "#f8f9fa" }    // Background
blue: { 600: "#2152ff" }   // Acción primaria
red: { 600: "#ea0606" }    // Peligro
green: { 600: "#17ad37" }  // Éxito
```

#### Componentes Personalizados
- Botones con gradientes
- Cards con sombras soft
- Dropdowns con transformaciones 3D
- Tipografía específica (h1-h6)
- Scrollbars personalizados

### Vite Configuration

**Archivo:** `vite.config.js`

```javascript
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        minify: 'esbuild',
        target: 'es2015',
        chunkSizeWarningLimit: 1000,
    },
    resolve: {
        dedupe: ['alpinejs'],
    },
});
```

**Optimizaciones:**
- Build con límite de memoria optimizado
- esbuild para minificación rápida
- Chunks manuales deshabilitados
- HMR overlay deshabilitado

### JavaScript Principal

**Archivo:** `resources/js/app.js`

#### Librerías Cargadas
```javascript
import Alpine from 'alpinejs';
import PerfectScrollbar from 'perfect-scrollbar';
import Chart from 'chart.js/auto';
import './modal-manager';  // Sistema de modales personalizado
```

#### Variables Globales
```javascript
window.Alpine = Alpine;
window.PerfectScrollbar = PerfectScrollbar;
window.Chart = Chart;
```

#### Sistema de Carga
- Prevención de FOUC (Flash of Unstyled Content)
- Loading states para formularios
- Transiciones suaves al cargar

### Estructura de Vistas

**Ubicación:** `resources/views/`

#### Layouts
- `layouts/app.blade.php` - Layout principal autenticado
- `layouts/guest.blade.php` - Layout para invitados
- `layouts/navigation.blade.php` - Navegación principal

#### Vistas de Usuarios
```
users/
├── index.blade.php           # Lista de usuarios
├── create.blade.php          # Crear usuario (no existe aún)
├── edit.blade.php            # Editar usuario
├── show.blade.php            # Ver detalles
├── deleteUsers.blade.php     # Usuarios eliminados
├── audit-history.blade.php   # Historial de auditoría
└── tableAuditoria.blade.php  # Componente tabla
```

#### Vistas de Productos
```
productos/
├── index.blade.php           # Lista de productos
├── create.blade.php          # Crear producto
├── edit.blade.php            # Editar producto
├── show.blade.php            # Ver detalles
├── deleteProducts.blade.php  # Productos eliminados
└── audit-history.blade.php   # Historial de auditoría
```

#### Vistas de Auditoría
```
audits/
├── by-user.blade.php         # Vista consolidada de auditorías
└── show.blade.php            # Detalles de auditoría
```

#### Dashboard
- `dashboard.blade.php` - Dashboard principal con estadísticas

#### Componentes Reutilizables
**Ubicación:** `resources/views/components/`

Laravel Blade Components disponibles para uso en vistas.

---

## ✅ Validaciones y Form Requests

### ValidarStoreUser

**Ubicación:** `app/Http/Requests/ValidarStoreUser.php`

#### Autorización
```php
public function authorize(): bool
{
    return Auth::user()->email === 'test@example.com';
}
```

#### Reglas de Validación
```php
'name' => 'required|string|max:255',
'email' => 'required|string|email|max:255|unique:users',
'password' => 'required|string|min:8|confirmed'
```

#### Mensajes Personalizados
- Mensajes en español
- Claridad en errores
- Atributos personalizados

#### Preparación de Datos
```php
protected function prepareForValidation(): void
{
    $this->merge([
        'name' => trim($this->name),
        'email' => strtolower(trim($this->email))
    ]);
}
```

---

### ValidarEditUser

**Ubicación:** `app/Http/Requests/ValidarEditUser.php`

Similar a ValidarStoreUser pero:
- Email único excepto el usuario actual
- Password opcional (solo si se quiere cambiar)
- Validación condicional

---

### ValidarStoreProducto

**Ubicación:** `app/Http/Requests/ValidarStoreProducto.php`

#### Autorización
```php
public function authorize(): bool
{
    return Auth::user()->email === 'test@example.com';
}
```

#### Reglas de Validación
```php
'nombre' => 'nullable|string|max:50',
'codigo' => 'required|string|unique:productos,codigo|max:10',
'cantidad' => 'required|integer|min:0|max:200',
'precio' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/'
```

#### Lógica Especial
```php
protected function prepareForValidation(): void
{
    $this->merge([
        'codigo' => strtoupper(trim($this->codigo)) . rand(100, 999)
    ]);
}
```
**Nota:** Agrega sufijo aleatorio al código antes de validar unicidad

#### Mensajes Personalizados
- Enfocados en el contexto de productos
- Mensajes claros sobre restricciones de stock
- Validación de decimales en precio

---

### ValidarEditProducto

**Ubicación:** `app/Http/Requests/ValidarEditProducto.php`

Similar a ValidarStoreProducto pero:
- Código único excepto el producto actual
- Mismas reglas de negocio

---

## ⚙️ Configuración

### Archivo .env Principal

```env
# Aplicación
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Base de Datos
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pry_conjunta
DB_USERNAME=root
DB_PASSWORD=

# Sesiones
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=database

# Colas
QUEUE_CONNECTION=database

# Email
MAIL_MAILER=log
```

### Configuración de Base de Datos

**Archivo:** `config/database.php`

Conexión PostgreSQL por defecto:
```php
'default' => env('DB_CONNECTION', 'sqlite'),

'connections' => [
    'pgsql' => [
        'driver' => 'pgsql',
        'url' => env('DB_URL'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '5432'),
        'database' => env('DB_DATABASE', 'laravel'),
        'username' => env('DB_USERNAME', 'root'),
        // ...
    ],
]
```

### Configuración de Auditoría

**Archivo:** `config/audit.php`

- Auditoría habilitada por defecto
- Resolvers para IP, User Agent y URL
- Guards: web y api
- Implementación personalizable

### Configuración de Permisos

**Archivo:** `config/permission.php`

- Modelos: Permission y Role de Spatie
- Cache habilitado
- Nombres de tabla configurables

### Testing

**Archivo:** `phpunit.xml`

```xml
<env name="APP_ENV" value="testing"/>
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
<env name="CACHE_STORE" value="array"/>
<env name="SESSION_DRIVER" value="array"/>
```

---

## 🚀 Características Principales

### 1. Gestión de Usuarios
- ✅ CRUD completo
- ✅ Soft delete con motivo
- ✅ Restauración con auditoría
- ✅ Eliminación permanente (requiere confirmación)
- ✅ Historial de cambios completo
- ✅ Búsqueda y filtrado
- ✅ Paginación configurable

### 2. Gestión de Productos
- ✅ CRUD completo
- ✅ Validación de stock (0-200)
- ✅ Validación de precio (2 decimales)
- ✅ Código único con sufijo aleatorio
- ✅ Soft delete con motivo
- ✅ Restauración
- ✅ Eliminación permanente
- ✅ Historial de cambios

### 3. Sistema de Auditoría
- ✅ Registro automático de cambios
- ✅ Tracking de quién, qué, cuándo y desde dónde
- ✅ Sistema de tags personalizado
- ✅ Motivos obligatorios para eliminaciones
- ✅ Vista consolidada de auditorías
- ✅ Filtros por evento, modelo, usuario
- ✅ Estadísticas de actividad

### 4. Seguridad
- ✅ Autenticación con Laravel Breeze
- ✅ Confirmación de contraseña en operaciones críticas
- ✅ Middleware de autorización
- ✅ Preparación para roles y permisos (Spatie)
- ✅ Protección CSRF
- ✅ Validación exhaustiva de inputs
- ✅ Logging de operaciones críticas

### 5. Dashboard
- ✅ Estadísticas en tiempo real
- ✅ Total de usuarios
- ✅ Total de productos
- ✅ Valor del inventario
- ✅ Stock total
- ✅ Interfaz amigable

### 6. UI/UX
- ✅ Diseño responsive
- ✅ Soft UI Dashboard theme
- ✅ Feedback visual (toasts/alerts)
- ✅ Loading states
- ✅ Modales para confirmaciones
- ✅ Scrollbars personalizados
- ✅ Animaciones suaves

---

## 🔄 Flujos de Trabajo

### Flujo: Crear Usuario

1. Usuario autenticado accede a `/users/create`
2. Completa formulario (name, email, password, password_confirmation)
3. Submit → ValidarStoreUser
4. Autorización: Solo `test@example.com`
5. Preparación: trim name, lowercase email
6. Validación de reglas
7. UserController@store
8. DB::beginTransaction()
9. User::create() con password hasheado
10. Auditoría automática (evento: created)
11. DB::commit()
12. Redirect a `/users` con mensaje de éxito

### Flujo: Eliminar Producto (Soft Delete)

1. Usuario hace clic en "Eliminar" en producto
2. Modal solicita: motivo + contraseña
3. Submit → ProductoController@destroy
4. Validación de request (motivo requerido, password requerido)
5. Verificación de contraseña con Hash::check()
6. DB::beginTransaction()
7. `$producto->auditComment = $request->motivo`
8. `$producto->delete()` (soft delete)
9. Auditoría automática con tags: `['motivo:xxx']`
10. DB::commit()
11. Redirect con mensaje de éxito

### Flujo: Restaurar Usuario

1. Usuario accede a `/usuarios-eliminados`
2. Lista usuarios con `deleted_at` != null
3. Clic en "Restaurar"
4. Modal solicita: motivo + contraseña
5. Submit → UserController@restore
6. Validación de contraseña
7. Verificación: no es el usuario actual
8. DB::beginTransaction()
9. `$user->auditComment = $request->motivo`
10. `$user->restore()`
11. Auditoría con evento: restored
12. DB::commit()
13. Redirect con éxito

### Flujo: Eliminación Permanente de Producto

1. Usuario en `/productos-eliminados`
2. Clic en "Eliminar Permanentemente"
3. Modal solicita: comentario (min 10 chars) + contraseña
4. Submit → ProductoController@forceDelete
5. Validación de password con Auth::user()
6. DB::beginTransaction()
7. Crear auditoría manual con evento: force_deleted
8. Log::info con todos los detalles
9. `$producto->forceDelete()` (eliminación real)
10. DB::commit()
11. Redirect con éxito

### Flujo: Ver Historial de Auditoría

1. Usuario accede a `/productos/{id}/audit-history`
2. ProductoController@auditHistory
3. Query: `$producto->audits()->with('user')`
4. Filtros opcionales: per_page, event
5. Paginación de resultados
6. Vista muestra:
   - Usuario que hizo el cambio
   - Tipo de evento
   - Valores anteriores vs nuevos
   - Timestamp
   - Tags (motivos)
   - IP y User Agent

---

## 📝 Comandos Útiles

### Instalación y Setup

```bash
# Instalar dependencias PHP
composer install

# Instalar dependencias Node
npm install

# Copiar archivo de entorno
cp .env.example .env

# Generar key de aplicación
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders
php artisan db:seed
php artisan db:seed --class=DashboardSeeder
```

### Desarrollo

```bash
# Servidor de desarrollo Laravel + Vite + Queue
composer dev

# O individual:
php artisan serve
php artisan queue:listen --tries=1
npm run dev

# Compilar assets para producción
npm run build
npm run build-prod

# Limpiar cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Testing

```bash
# Ejecutar tests con Pest
composer test
# O directamente:
php artisan test
vendor/bin/pest
```

### Base de Datos

```bash
# Crear migración
php artisan make:migration create_table_name

# Ejecutar migraciones
php artisan migrate

# Rollback última migración
php artisan migrate:rollback

# Refrescar BD (drop + migrate)
php artisan migrate:fresh

# Refrescar con seed
php artisan migrate:fresh --seed

# Ver estado de migraciones
php artisan migrate:status
```

### Auditoría

```bash
# Instalar tablas de auditoría
php artisan vendor:publish --provider="OwenIt\Auditing\AuditingServiceProvider" --tag="migrations"
php artisan migrate

# Publicar config
php artisan vendor:publish --provider="OwenIt\Auditing\AuditingServiceProvider" --tag="config"
```

### Permisos (Spatie)

```bash
# Publicar migraciones
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# Limpiar cache de permisos
php artisan permission:cache-reset

# Crear permiso desde Tinker
php artisan tinker
>>> Permission::create(['name' => 'edit articles']);
>>> Role::create(['name' => 'admin']);
```

### Mantenimiento

```bash
# Ver logs en tiempo real
php artisan pail

# Tinker (REPL interactivo)
php artisan tinker

# Optimizar aplicación
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Limpiar optimizaciones
php artisan optimize:clear
```

### Docker (Laravel Sail)

```bash
# Iniciar contenedores
./vendor/bin/sail up -d

# Detener contenedores
./vendor/bin/sail down

# Ejecutar comandos artisan
./vendor/bin/sail artisan migrate

# Ejecutar composer
./vendor/bin/sail composer install

# Ejecutar npm
./vendor/bin/sail npm install
```

---

## 📊 Estadísticas del Proyecto

### Archivos de Código
- **Modelos:** 2 (User, Producto)
- **Controladores:** 7 principales
- **Migraciones:** 7
- **Form Requests:** 4
- **Rutas:** 20+ definidas
- **Vistas Blade:** 15+ archivos

### Paquetes Composer
- **require:** 7 paquetes
- **require-dev:** 8 paquetes
- **Total vendor packages:** 50+ (con dependencias)

### Paquetes NPM
- **devDependencies:** 11 paquetes
- **dependencies:** 5 paquetes

### Líneas de Código (Aproximado)
- **Controllers:** ~1500 líneas
- **Models:** ~300 líneas
- **Blade Views:** ~2000+ líneas
- **JavaScript:** ~500 líneas
- **CSS:** ~1000 líneas (incluyendo Tailwind config)

---

## 🎯 Casos de Uso Principales

### 1. Administrador de Inventario
- Ver dashboard con estadísticas
- Crear productos nuevos
- Actualizar stock y precios
- Ver historial de cambios
- Eliminar productos obsoletos
- Restaurar productos eliminados por error

### 2. Administrador de Sistema
- Gestionar usuarios
- Asignar roles (preparado con Spatie)
- Ver toda la actividad del sistema
- Auditar cambios críticos
- Eliminar permanentemente datos

### 3. Auditor
- Revisar historial completo de cambios
- Filtrar por tipo de evento
- Filtrar por usuario
- Filtrar por modelo (User/Producto)
- Ver detalles de cada cambio
- Identificar patrones de uso

---

## 🔮 Próximas Mejoras Sugeridas

### Funcionalidad
- [ ] Implementar sistema de roles activo (ya está Spatie instalado)
- [ ] Agregar categorías de productos
- [ ] Implementar alertas de stock bajo
- [ ] Reportes exportables (PDF, Excel)
- [ ] API REST para integración externa
- [ ] Notificaciones por email
- [ ] Dashboard con gráficos (Chart.js ya está instalado)

### Seguridad
- [ ] Autenticación de dos factores (2FA)
- [ ] Rate limiting en operaciones críticas
- [ ] Logs de seguridad separados
- [ ] Políticas de contraseña más robustas

### UI/UX
- [ ] Dark mode completo
- [ ] Vista de tabla responsive mejorada
- [ ] Búsqueda en tiempo real (AJAX)
- [ ] Exportar datos a CSV/Excel
- [ ] Impresión de reportes

### Testing
- [ ] Tests unitarios para modelos
- [ ] Tests de feature para CRUD
- [ ] Tests de integración para auditoría
- [ ] Tests E2E con Pest

---

## 📚 Recursos y Documentación

### Laravel 12
- Documentación oficial: https://laravel.com/docs/12.x

### Paquetes Utilizados
- Laravel Auditing: https://laravel-auditing.com/
- Spatie Permission: https://spatie.be/docs/laravel-permission/
- Laravel Breeze: https://laravel.com/docs/12.x/starter-kits#laravel-breeze
- TailwindCSS: https://tailwindcss.com/docs
- Alpine.js: https://alpinejs.dev/
- Chart.js: https://www.chartjs.org/

---

## 🤝 Convenciones del Proyecto

### Nombres de Archivos
- **Controllers:** PascalCase + "Controller" (ej: `ProductoController.php`)
- **Models:** PascalCase singular (ej: `Producto.php`)
- **Migrations:** snake_case con timestamp (ej: `2025_06_17_163403_create_productos_table.php`)
- **Views:** kebab-case (ej: `audit-history.blade.php`)
- **Form Requests:** PascalCase descriptivo (ej: `ValidarStoreProducto.php`)

### Nombres de Rutas
- **Recurso:** plurales en español (ej: `/productos`, `/usuarios`)
- **Rutas especiales:** kebab-case (ej: `/audit-history`, `/productos-eliminados`)

### Nombres de Métodos
- CRUD estándar: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`
- Métodos extra: camelCase descriptivo (ej: `auditHistory`, `deletedUsers`)

### Base de Datos
- **Tablas:** snake_case plural (ej: `productos`, `users`)
- **Columnas:** snake_case (ej: `created_at`, `deleted_at`)
- **Foreign keys:** singular_id (ej: `user_id`)

---

## ⚠️ Notas Importantes

### Autorización Actual
```php
// En Form Requests
public function authorize(): bool
{
    return Auth::user()->email === 'test@example.com';
}
```
**⚠️ ADVERTENCIA:** Solo el usuario `test@example.com` puede crear/editar usuarios y productos. Esto debe ser modificado en producción usando el sistema de roles de Spatie.

### Lógica Especial en Productos
- El código del producto se modifica automáticamente:
  - En `store()`: Se agrega sufijo aleatorio antes de guardar
  - En `update()`: Se agrega sufijo aleatorio al código existente
- Esta lógica parece ser temporal para testing

### Transacciones de Base de Datos
Todas las operaciones CRUD usan transacciones:
```php
DB::beginTransaction();
try {
    // operaciones
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    // manejo de error
}
```

### Soft Deletes
- Todos los registros eliminados se marcan con `deleted_at`
- Se pueden restaurar
- Solo con confirmación se eliminan permanentemente
- La eliminación permanente crea auditoría manual

---

## 🐛 Issues Conocidos

1. **Autorización hardcodeada:** Solo `test@example.com` puede crear/editar
2. **Lógica de código de producto:** Modificación automática con random puede causar inconsistencias
3. **Falta vista create de usuarios:** Referenciada en rutas pero no existe el archivo
4. **Sin roles activos:** Spatie Permission instalado pero no implementado
5. **Sin tests:** Estructura de Pest configurada pero sin tests escritos

---

## 🔧 Troubleshooting

### Error: "Class 'App\Models\...' not found"
```bash
composer dump-autoload
```

### Error de migraciones
```bash
php artisan migrate:fresh
# O si hay datos importantes:
php artisan migrate:rollback
php artisan migrate
```

### Assets no se compilan
```bash
npm install
npm run build
```

### Error de permisos en storage/logs
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows
# Verificar permisos de escritura en propiedades de carpeta
```

### Cache de configuración problemático
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## 📧 Contacto y Soporte

**Repositorio:** OppaDev/pry_2p  
**Branch:** main  
**Ambiente:** Laragon (Windows)

---

## 📄 Licencia

Este proyecto utiliza Laravel Framework que está bajo licencia MIT.

---

**Documento generado automáticamente el 2 de octubre de 2025**  
**Versión del documento:** 1.0.0  
**Laravel Version:** 12.0

