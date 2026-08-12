#!/usr/bin/env sh
set -e

# Helper: wait for the database to become available (simple loop)
wait_for_db() {
  if [ -z "${DB_HOST}" ]; then
    return 0
  fi

  echo "Waiting for DB at ${DB_HOST}:${DB_PORT:-5432}..."
  tries=0
  until php -r "try { \$pdo = new PDO('pgsql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT')?:5432) . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); echo 'ok'; } catch (Exception \$e) { exit(1); }" 2>/dev/null; do
    tries=$((tries+1))
    if [ "${tries}" -gt 30 ]; then
      echo "Timed out waiting for DB"
      break
    fi
    sleep 1
  done
}

# Ensure storage subdirectories explicitly exist on container startup
mkdir -p /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

# Ensure permissions (useful in some container runtimes)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

# Clear and rebuild caches to ensure correct config
if [ -f artisan ]; then
  php artisan config:clear || true
  php artisan route:clear || true
  php artisan view:clear || true
  # Rebuild caches (safe operation)
  php artisan config:cache || true
  php artisan route:cache || true
fi

# Optionally run migrations if explicitly requested via env var
if [ "${RUN_MIGRATIONS}" = "true" ]; then
  echo "RUN_MIGRATIONS=true: attempting to run migrations"
  wait_for_db
  if [ -f artisan ]; then
    php artisan migrate --force || echo "Migrations failed or not needed"
  fi
fi

# Finally exec the container's main process (Apache)
exec "$@"