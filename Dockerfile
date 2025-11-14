# ============================================
# DOCKERFILE MULTI-STAGE PARA INFERNO CLUB
# Laravel 12 + PHP 8.2 + PostgreSQL
# ============================================

# ============================================
# STAGE 1: Node.js - Build Frontend Assets
# ============================================
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copiar package files
COPY package*.json ./

# Instalar todas las dependencias (necesarias para build)
RUN npm ci

# Copiar archivos necesarios para build
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
COPY tailwind.config.js ./
COPY postcss.config.js ./

# Build assets con Vite
RUN npm run build

# ============================================
# STAGE 2: Composer - Install PHP Dependencies
# ============================================
FROM composer:latest AS composer-builder

WORKDIR /app

# Copiar composer files
COPY composer.json composer.lock ./

# Instalar dependencias de producción
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# ============================================
# STAGE 3: Production Image
# ============================================
FROM php:8.2-fpm-alpine

LABEL maintainer="Inferno Club <admin@infernoclub.com>"
LABEL description="Sistema de Gestión POS + Facturación Electrónica SRI Ecuador"
LABEL version="1.0.0"

# Variables de entorno
ENV APP_ENV=production \
    APP_DEBUG=false \
    DB_CONNECTION=pgsql \
    DB_HOST=postgres \
    DB_PORT=5432 \
    CACHE_STORE=database \
    SESSION_DRIVER=database \
    QUEUE_CONNECTION=database \
    PHP_OPCACHE_ENABLE=1 \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=0 \
    PHP_OPCACHE_MAX_ACCELERATED_FILES=10000 \
    PHP_OPCACHE_MEMORY_CONSUMPTION=192 \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE=10

# Instalar dependencias del sistema
RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    libpng-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    icu-dev \
    libxml2-dev \
    oniguruma-dev \
    curl \
    bash \
    && rm -rf /var/cache/apk/*

# Instalar extensiones PHP requeridas
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        intl \
        opcache \
        pcntl \
        bcmath \
        soap \
    && docker-php-ext-enable opcache

# Configurar PHP para producción
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Configuración custom de PHP
COPY docker/php/custom.ini $PHP_INI_DIR/conf.d/custom.ini

# Crear usuario no-root
RUN addgroup -g 1000 www && \
    adduser -u 1000 -G www -s /bin/sh -D www

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar vendor desde composer-builder
COPY --from=composer-builder --chown=www:www /app/vendor ./vendor

# Copiar assets buildeados desde node-builder
COPY --from=node-builder --chown=www:www /app/public/build ./public/build

# Copiar código de la aplicación
COPY --chown=www:www . .

# Crear directorios necesarios y establecer permisos
RUN mkdir -p storage/app/public \
    storage/app/facturas \
    storage/framework/{cache,sessions,views} \
    storage/logs \
    bootstrap/cache \
    && chown -R www:www storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Optimizaciones de Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Cambiar a usuario no-root
USER www

# Healthcheck
HEALTHCHECK --interval=30s --timeout=3s --retries=3 \
    CMD php artisan inspire || exit 1

# Exponer puerto PHP-FPM
EXPOSE 9000

# Comando de inicio
CMD ["php-fpm"]
