# 🐳 Docker Build & Push - Inferno Club

Guía para buildear y publicar la imagen Docker en Docker Hub.

## 📋 Prerequisitos

1. **Docker instalado** (versión 20.10+)
2. **Cuenta en Docker Hub**: https://hub.docker.com/
3. **Login en Docker CLI**:
   ```bash
   docker login
   ```

---

## 🏗️ Build de la Imagen

### 1. Build Local (Testing)

```bash
# Build de la imagen
docker build -t inferno-club:latest .

# Build con tag específico
docker build -t inferno-club:v1.0.0 .

# Verificar la imagen
docker images | grep inferno-club
```

### 2. Build Multi-plataforma (Producción)

```bash
# Crear builder (primera vez)
docker buildx create --name inferno-builder --use

# Build para múltiples arquitecturas
docker buildx build \
  --platform linux/amd64,linux/arm64 \
  -t tu-usuario/inferno-club:latest \
  -t tu-usuario/inferno-club:v1.0.0 \
  --push \
  .
```

---

## 📤 Push a Docker Hub

### Opción 1: Push Manual

```bash
# 1. Tag la imagen con tu usuario de Docker Hub
docker tag inferno-club:latest tu-usuario/inferno-club:latest
docker tag inferno-club:latest tu-usuario/inferno-club:v1.0.0

# 2. Push a Docker Hub
docker push tu-usuario/inferno-club:latest
docker push tu-usuario/inferno-club:v1.0.0
```

### Opción 2: Build + Push en un comando

```bash
docker build -t tu-usuario/inferno-club:latest -t tu-usuario/inferno-club:v1.0.0 . && \
docker push tu-usuario/inferno-club:latest && \
docker push tu-usuario/inferno-club:v1.0.0
```

---

## 🧪 Probar la Imagen Localmente

```bash
# Crear red
docker network create inferno-net

# Iniciar PostgreSQL
docker run -d \
  --name inferno-postgres \
  --network inferno-net \
  -e POSTGRES_DB=inferno_db \
  -e POSTGRES_USER=postgres \
  -e POSTGRES_PASSWORD=secretpassword \
  -p 5432:5432 \
  postgres:16-alpine

# Iniciar aplicación
docker run -d \
  --name inferno-app \
  --network inferno-net \
  -e APP_KEY="base64:$(openssl rand -base64 32)" \
  -e DB_HOST=inferno-postgres \
  -e DB_DATABASE=inferno_db \
  -e DB_USERNAME=postgres \
  -e DB_PASSWORD=secretpassword \
  -p 9000:9000 \
  tu-usuario/inferno-club:latest

# Ver logs
docker logs -f inferno-app
```

---

## 🎯 Estrategia de Versionado

### Semantic Versioning

```bash
# Major version (cambios incompatibles)
docker tag inferno-club:latest tu-usuario/inferno-club:v2.0.0
docker tag inferno-club:latest tu-usuario/inferno-club:v2.0
docker tag inferno-club:latest tu-usuario/inferno-club:v2

# Minor version (nuevas funcionalidades)
docker tag inferno-club:latest tu-usuario/inferno-club:v1.1.0
docker tag inferno-club:latest tu-usuario/inferno-club:v1.1

# Patch version (bug fixes)
docker tag inferno-club:latest tu-usuario/inferno-club:v1.0.1
```

### Tags Recomendados

```bash
tu-usuario/inferno-club:latest      # Última versión estable
tu-usuario/inferno-club:v1.0.0      # Versión específica
tu-usuario/inferno-club:dev         # Branch development
tu-usuario/inferno-club:staging     # Branch staging
```

---

## 📊 Información de la Imagen

```bash
# Tamaño de la imagen
docker images inferno-club

# Inspeccionar layers
docker history tu-usuario/inferno-club:latest

# Ver metadata
docker inspect tu-usuario/inferno-club:latest
```

**Tamaño esperado**: ~150-200 MB (gracias al multi-stage build)

---

## 🔐 Variables de Entorno Requeridas

Al ejecutar el contenedor, debes pasar estas variables:

```bash
# Mínimas para iniciar
-e APP_KEY="base64:..."
-e DB_HOST=postgres
-e DB_DATABASE=inferno_db
-e DB_USERNAME=postgres
-e DB_PASSWORD=secretpassword

# Facturación SRI
-e SRI_MODO_PRUEBA=true
-e SRI_RUC_EMPRESA=1234567890001
-e SRI_RAZON_SOCIAL="INFERNO CLUB S.A."
-e SRI_ESTABLECIMIENTO=001
-e SRI_PUNTO_EMISION=001
```

---

## 🚀 Deploy en Producción

### Pull desde Docker Hub

```bash
# En tu servidor de producción
docker pull tu-usuario/inferno-club:v1.0.0

# Ejecutar con docker-compose.yml
docker-compose up -d
```

### Actualizar a nueva versión

```bash
# Pull nueva versión
docker pull tu-usuario/inferno-club:v1.1.0

# Actualizar tag en docker-compose.yml
# image: tu-usuario/inferno-club:v1.1.0

# Recrear contenedor
docker-compose up -d --force-recreate app
```

---

## 🔄 GitHub Actions (CI/CD)

### Archivo: `.github/workflows/docker.yml`

```yaml
name: Docker Build & Push

on:
  push:
    branches: [ main ]
    tags: [ 'v*' ]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Set up Docker Buildx
        uses: docker/setup-buildx-action@v2
      
      - name: Login to Docker Hub
        uses: docker/login-action@v2
        with:
          username: ${{ secrets.DOCKERHUB_USERNAME }}
          password: ${{ secrets.DOCKERHUB_TOKEN }}
      
      - name: Extract metadata
        id: meta
        uses: docker/metadata-action@v4
        with:
          images: tu-usuario/inferno-club
      
      - name: Build and push
        uses: docker/build-push-action@v4
        with:
          context: .
          platforms: linux/amd64,linux/arm64
          push: true
          tags: ${{ steps.meta.outputs.tags }}
          labels: ${{ steps.meta.outputs.labels }}
```

---

## 🐞 Troubleshooting

### Error: "permission denied"
```bash
sudo chown -R $(whoami):$(whoami) storage bootstrap/cache
```

### Error: "build failed"
```bash
# Limpiar cache de Docker
docker builder prune -a

# Rebuild sin cache
docker build --no-cache -t inferno-club:latest .
```

### Imagen muy grande
```bash
# Analizar capas
docker history inferno-club:latest --no-trunc

# Usar dive para análisis
docker run --rm -it -v /var/run/docker.sock:/var/run/docker.sock wagoodman/dive inferno-club:latest
```

---

## 📝 Comandos Útiles

```bash
# Build rápido para testing
docker build -t test .

# Shell dentro del contenedor
docker exec -it inferno-app sh

# Ver variables de entorno
docker exec inferno-app env

# Ejecutar migrations
docker exec inferno-app php artisan migrate

# Ejecutar seeders
docker exec inferno-app php artisan db:seed

# Clear cache
docker exec inferno-app php artisan cache:clear
docker exec inferno-app php artisan config:clear
```

---

## ✅ Checklist Pre-Push

- [ ] Tests pasando: `php artisan test`
- [ ] Build exitoso localmente
- [ ] Imagen probada con PostgreSQL
- [ ] Variables de entorno documentadas
- [ ] Tamaño de imagen < 300MB
- [ ] Versión taggeada correctamente
- [ ] Changelog actualizado
- [ ] README actualizado

---

## 🔗 Links Útiles

- Docker Hub: https://hub.docker.com/
- Docker Docs: https://docs.docker.com/
- Multi-stage builds: https://docs.docker.com/build/building/multi-stage/
- Buildx: https://docs.docker.com/buildx/working-with-buildx/
