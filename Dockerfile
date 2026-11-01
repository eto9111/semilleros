# Dockerfile — Baloncesto YOLO · Semillero FOCUS CESMAG
# Imagen base: PHP 8.2 + Apache
FROM php:8.2-apache

# Instalar dependencias del sistema y la extension pdo_pgsql
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer desde su imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar todo el proyecto al contenedor
COPY . .

# Instalar dependencias PHP de produccion (sin paquetes de desarrollo)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configurar Apache: usar public/ como DocumentRoot
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/public>|g' \
    /etc/apache2/apache2.conf 2>/dev/null || true \
    && a2enmod rewrite

# Permisos correctos para Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/public

# Puerto expuesto (Render lo mapea automaticamente)
EXPOSE 80
