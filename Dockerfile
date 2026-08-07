# Stage 1: Install PHP dependencies with Composer
FROM composer:2 as vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --ignore-platform-reqs --no-scripts

COPY . ./
RUN composer dump-autoload --optimize --no-interaction


# Stage 2: Build frontend assets with Node & Vite
FROM node:20-alpine as assets

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY --from=vendor /app ./
RUN npm run build


# Stage 3: Runtime stage - serve Laravel through Apache
FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zlib1g-dev \
    libicu-dev \
    libpq-dev \
    && docker-php-ext-install intl pdo_mysql pdo_pgsql mbstring zip exif pcntl \
    && a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV PORT 8080

# Configure Apache virtual host explicitly for port 8080 and /public
RUN echo '<VirtualHost *:8080>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options -Indexes +FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf \
    && echo "Listen 8080" > /etc/apache2/ports.conf

WORKDIR /var/www/html

# Copy application code with compiled vendor packages and Vite manifest assets
COPY --from=assets /app /var/www/html

# Ensure storage and cache directories exist and set required permissions
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080

# Clear stale configuration caches, run migrations, and launch Apache
CMD sh -c "php artisan config:clear && php artisan migrate --force && apache2-foreground"