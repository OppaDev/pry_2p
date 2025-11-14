# 🐳 INSTRUCCIONES DE DESPLIEGUE - INFERNO CLUB

Guía completa para desplegar la aplicación Inferno Club usando Docker Compose.

---

## 📋 REQUISITOS PREVIOS

- **Docker Engine** 20.10+ instalado
- **Docker Compose** 2.0+ instalado
- Al menos **2GB de RAM** disponible
- Puerto **80** (HTTP) y **5432** (PostgreSQL) disponibles

### Verificar instalación:
```bash
docker --version
docker-compose --version
```

---

## 🚀 DESPLIEGUE RÁPIDO

### 1. Configurar Variables de Entorno

Copia el archivo de ejemplo y configura tus valores:

```bash
cp .env.example .env
```

**IMPORTANTE:** Edita el archivo `.env` y configura:

#### Variables Críticas:
```env
APP_KEY=base64:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
DB_PASSWORD=CAMBIAR_CONTRASEÑA_SEGURA
SRI_RUC=1234567890001
SRI_RAZON_SOCIAL="TU EMPRESA S.A."
```

#### Generar APP_KEY:
Si tienes PHP localmente:
```bash
php artisan key:generate --show
```

O genera una clave base64 online y agrégala con el prefijo `base64:`

---

### 2. Iniciar los Contenedores

```bash
# Descargar imágenes y crear contenedores
docker-compose up -d

# Ver logs en tiempo real
docker-compose logs -f
```

**Servicios iniciados:**
- `inferno-postgres` - Base de datos PostgreSQL 16
- `inferno-app` - Aplicación Laravel (PHP-FPM)
- `inferno-nginx` - Servidor web Nginx

---

### 3. Configurar Base de Datos

Ejecuta las migraciones y seeders:

```bash
# Ejecutar migraciones
docker-compose exec app php artisan migrate --force

# Ejecutar seeders (usuarios, roles, permisos, categorías)
docker-compose exec app php artisan db:seed --force

# O ejecutar todo de una vez (borra y recrea todo)
docker-compose exec app php artisan migrate:fresh --seed --force
```

---

### 4. Verificar Instalación

Abre tu navegador en: **http://localhost**

#### Usuarios de prueba:
| Email | Contraseña | Rol |
|-------|------------|-----|
| admin@infernoclub.com | password123 | Administrador |
| vendedor@infernoclub.com | password123 | Vendedor |
| bodega@infernoclub.com | password123 | Jefe Bodega |

---

## 📦 COMANDOS ÚTILES

### Gestión de Contenedores

```bash
# Ver estado de servicios
docker-compose ps

# Detener servicios
docker-compose stop

# Iniciar servicios detenidos
docker-compose start

# Reiniciar servicios
docker-compose restart

# Detener y eliminar contenedores (datos persisten en volumes)
docker-compose down

# Eliminar TODO incluyendo volúmenes (⚠️ BORRA LA BASE DE DATOS)
docker-compose down -v
```

---

### Logs y Debugging

```bash
# Ver logs de todos los servicios
docker-compose logs

# Ver logs de un servicio específico
docker-compose logs app
docker-compose logs postgres
docker-compose logs nginx

# Seguir logs en tiempo real
docker-compose logs -f app

# Ver últimas 100 líneas
docker-compose logs --tail=100 app
```

---

### Acceso a Contenedores

```bash
# Acceder al contenedor de la aplicación
docker-compose exec app sh

# Acceder a PostgreSQL
docker-compose exec postgres psql -U postgres -d inferno_db

# Ejecutar comandos Artisan
docker-compose exec app php artisan <comando>
```

---

### Comandos Laravel Comunes

```bash
# Limpiar caché
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Optimizar para producción
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Ver rutas
docker-compose exec app php artisan route:list

# Crear usuario manualmente
docker-compose exec app php artisan tinker
>>> User::create(['name'=>'Nuevo', 'email'=>'nuevo@mail.com', 'password'=>bcrypt('123456')]);

# Verificar migraciones pendientes
docker-compose exec app php artisan migrate:status
```

---

## 🔧 CONFIGURACIÓN AVANZADA

### Despliegue con Nginx Proxy Manager

Si usas **Nginx Proxy Manager** como proxy reverso:

1. El docker-compose expone el puerto **8080** (configurable en `.env`)
2. En Nginx Proxy Manager, crea un Proxy Host:
   - **Domain Names**: `tu-dominio.com`
   - **Scheme**: `http`
   - **Forward Hostname / IP**: `inferno-nginx` (o IP del servidor)
   - **Forward Port**: `8080`
   - **Cache Assets**: ✅ Enabled
   - **Block Common Exploits**: ✅ Enabled
   - **Websockets Support**: ❌ Disabled

3. En la pestaña **SSL**:
   - Request a new SSL Certificate (Let's Encrypt)
   - Force SSL: ✅ Enabled
   - HTTP/2 Support: ✅ Enabled

4. Actualiza `.env` con tu dominio:
   ```env
   APP_URL=https://tu-dominio.com
   ```

5. Reinicia los contenedores:
   ```bash
   docker-compose restart app
   ```

---

### Cambiar Puerto HTTP

Edita `.env`:
```env
NGINX_PORT=8080
```

Luego reinicia:
```bash
docker-compose up -d nginx
```

Accede en: **http://localhost:8080**

---

### Habilitar SSL/HTTPS

1. Coloca tus certificados en `nginx/certs/`:
   - `certificate.crt`
   - `private.key`

2. Crea `nginx/ssl.conf`:
```nginx
server {
    listen 443 ssl http2;
    server_name tu-dominio.com;

    ssl_certificate /etc/nginx/certs/certificate.crt;
    ssl_certificate_key /etc/nginx/certs/private.key;
    
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # ... resto de configuración igual a default.conf
}
```

3. Agrega el volumen en `docker-compose.yml`:
```yaml
nginx:
  volumes:
    - ./nginx/certs:/etc/nginx/certs:ro
    - ./nginx/ssl.conf:/etc/nginx/conf.d/ssl.conf:ro
```

---

### Backup de Base de Datos

```bash
# Crear backup
docker-compose exec postgres pg_dump -U postgres inferno_db > backup_$(date +%Y%m%d_%H%M%S).sql

# Restaurar backup
docker-compose exec -T postgres psql -U postgres inferno_db < backup_20250113_120000.sql
```

---

### Actualizar a Nueva Versión

```bash
# Editar .env y cambiar versión
APP_VERSION=v1.1.0

# Descargar nueva imagen y recrear contenedor
docker-compose pull app
docker-compose up -d app

# Ejecutar migraciones nuevas (si hay)
docker-compose exec app php artisan migrate --force

# Limpiar caché
docker-compose exec app php artisan optimize:clear
docker-compose exec app php artisan optimize
```

---

## 🔍 SOLUCIÓN DE PROBLEMAS

### Error: "Puerto 80 ya en uso"

```bash
# Windows: Detener IIS o Apache
net stop http

# O cambiar puerto en .env
NGINX_PORT=8080
```

---

### Error: "Puerto 5432 ya en uso"

```bash
# Cambiar puerto de PostgreSQL en .env
DB_PORT=5433

# Actualizar docker-compose.yml
ports:
  - "5433:5432"
```

---

### Error: "SQLSTATE[08006] Connection refused"

Verifica que PostgreSQL esté saludable:
```bash
docker-compose ps
docker-compose logs postgres

# Reiniciar servicios
docker-compose restart postgres app
```

---

### Error: "No application encryption key has been specified"

Genera una nueva `APP_KEY`:
```bash
docker-compose exec app php artisan key:generate --show

# Copia el resultado y pégalo en .env con el prefijo base64:
APP_KEY=base64:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
```

Luego reinicia:
```bash
docker-compose restart app
```

---

### Contenedor se reinicia constantemente

```bash
# Ver logs del contenedor
docker-compose logs app

# Verificar health check
docker inspect inferno-app | grep -A 10 Health

# Acceder al contenedor (si está corriendo)
docker-compose exec app sh

# Ver procesos
docker-compose exec app ps aux
```

---

### Permisos de archivos en storage/

```bash
# Dar permisos correctos
docker-compose exec app chown -R www:www /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/bootstrap/cache
```

---

## 📊 MONITOREO

### Ver uso de recursos

```bash
# Stats en tiempo real
docker stats inferno-app inferno-postgres inferno-nginx

# Espacio en disco de volúmenes
docker system df -v

# Inspeccionar volúmenes
docker volume ls
docker volume inspect despliegue_postgres_data
```

---

### Health Checks

```bash
# Verificar salud de la aplicación
curl http://localhost/health

# Verificar PostgreSQL
docker-compose exec postgres pg_isready -U postgres

# Verificar Nginx
curl -I http://localhost
```

---

## 🔒 SEGURIDAD EN PRODUCCIÓN

### Checklist antes de producción:

- [ ] Cambiar `APP_ENV=production`
- [ ] Configurar `APP_DEBUG=false`
- [ ] Generar `APP_KEY` único y seguro
- [ ] Cambiar contraseña de `DB_PASSWORD`
- [ ] Configurar dominio real en `APP_URL`
- [ ] Habilitar SSL/HTTPS
- [ ] Cambiar contraseñas de usuarios de prueba
- [ ] Configurar firewall (solo puertos 80, 443)
- [ ] Configurar backups automáticos
- [ ] Revisar permisos de archivos
- [ ] Configurar SMTP real (no Gmail personal)
- [ ] Cambiar `SRI_AMBIENTE=2` (PRODUCCIÓN SRI)

---

## 📞 SOPORTE

Para problemas o preguntas:

1. Revisa los logs: `docker-compose logs -f`
2. Verifica configuración: `.env` y `docker-compose.yml`
3. Consulta documentación oficial de Laravel: https://laravel.com/docs

---

## 📝 NOTAS ADICIONALES

### Volúmenes Persistentes

Los datos se guardan en volúmenes Docker:

- `postgres_data` - Base de datos completa
- `storage_data` - Archivos subidos
- `facturas_data` - Facturas XML/PDF generadas
- `logs_data` - Logs de la aplicación

Estos datos **persisten** incluso si eliminas los contenedores con `docker-compose down`.

Para eliminar TODO (⚠️ **datos incluidos**):
```bash
docker-compose down -v
```

---

### Desarrollo Local vs Producción

Este `docker-compose.yml` está configurado para **producción**. 

Para desarrollo local, considera:
- Montar código fuente como volumen (hot-reload)
- Habilitar `APP_DEBUG=true`
- Usar `APP_ENV=local`
- Exponer puerto de PostgreSQL para herramientas GUI

---

**¡Listo! Tu aplicación Inferno Club está corriendo en Docker 🎉**
