# Stage 1: Install PHP dependencies with Composer
FROM composer:2 as vendor

WORKDIR /app

COPY composer.json composer.lock ./
# Install PHP deps without dev and without running scripts here
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --ignore-platform-reqs --no-scripts

# Copy the rest of the PHP app and dump optimized autoload
COPY . ./
RUN composer dump-autoload --optimize --no-interaction


# Stage 2: Build frontend assets with Node & Vite
FROM node:20-alpine as assets

WORKDIR /app

# Install node deps based on package.json only for better caching
COPY package*.json ./
RUN npm ci --silent

# Copy php app (including resources) from vendor stage and build assets
COPY --from=vendor /app ./
RUN npm run build --silent


# Stage 3: Runtime stage - serve Laravel through Apache
FROM php:8.2-apache

# Install system dependencies and PHP extensions
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
    ca-certificates \
    && docker-php-ext-install intl pdo_mysql pdo_pgsql mbstring zip exif pcntl \
    && a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV PORT 8080

# Configure Apache virtual host explicitly for port 8080 and /public
RUN printf '%s\n' "<VirtualHost *:8080>" \
    "    DocumentRoot /var/www/html/public" \
    "    <Directory /var/www/html/public>" \
    "        Options -Indexes +FollowSymLinks" \
    "        AllowOverride All" \
    "        Require all granted" \
    "    </Directory>" \
    "    ErrorLog ${APACHE_LOG_DIR}/error.log" \
    "    CustomLog ${APACHE_LOG_DIR}/access.log combined" \
    "</VirtualHost>" > /etc/apache2/sites-available/000-default.conf \
    && printf 'Listen 8080\n' > /etc/apache2/ports.conf

WORKDIR /var/www/html

# Copy application code with compiled vendor packages and built assets
COPY --from=assets /app /var/www/html

# Explicitly ensure compiled Vite build files are present in public/build
COPY --from=assets /app/public/build /var/www/html/public/build

# Copy entrypoint script and make it executable
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Ensure storage and cache directories exist and set required permissions
RUN mkdir -p /var/www/html/storage/framework/views \
             /var/www/html/storage/framework/cache/data \
             /var/www/html/storage/framework/sessions \
             /var/www/html/storage/logs \
             /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]