#!/bin/sh
set -eu

PORT="${PORT:-8080}"

cat > /etc/apache2/ports.conf <<EOF
Listen ${PORT}
EOF

cat > /etc/apache2/sites-available/000-default.conf <<EOF
<VirtualHost *:${PORT}>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog /proc/self/fd/2
    CustomLog /proc/self/fd/1 combined
</VirtualHost>
EOF

rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf
ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

rm -f /var/www/html/bootstrap/cache/*.php

if [ -z "${DB_CONNECTION:-}" ]; then
    if [ -n "${MYSQLHOST:-}" ]; then
        export DB_CONNECTION=mysql
    elif [ -n "${DB_URL:-}" ] && echo "$DB_URL" | grep -Eq '^mysql(i)?://'; then
        export DB_CONNECTION=mysql
    elif [ -n "${DATABASE_URL:-}" ] && echo "$DATABASE_URL" | grep -Eq '^mysql(i)?://'; then
        export DB_URL="$DATABASE_URL"
        export DB_CONNECTION=mysql
    elif [ -n "${MYSQL_URL:-}" ] && echo "$MYSQL_URL" | grep -Eq '^mysql(i)?://'; then
        export DB_URL="$MYSQL_URL"
        export DB_CONNECTION=mysql
    fi
fi

if [ "${DB_CONNECTION:-sqlite}" = "mysql" ]; then
    export DB_HOST="${DB_HOST:-${MYSQLHOST:-}}"
    export DB_PORT="${DB_PORT:-${MYSQLPORT:-3306}}"
    export DB_DATABASE="${DB_DATABASE:-${MYSQLDATABASE:-}}"
    export DB_USERNAME="${DB_USERNAME:-${MYSQLUSER:-}}"
    export DB_PASSWORD="${DB_PASSWORD:-${MYSQLPASSWORD:-}}"
fi

if [ "${APP_ENV:-production}" = "production" ] \
    && [ "${DB_CONNECTION:-sqlite}" = "sqlite" ] \
    && [ "${ALLOW_EPHEMERAL_SQLITE:-false}" != "true" ]; then
    echo "Refusing to start production with SQLite inside the container." >&2
    echo "Railway redeploys replace the container filesystem, so SQLite data stored here will disappear." >&2
    echo "Attach a Railway MySQL database and set DB_CONNECTION=mysql, or set ALLOW_EPHEMERAL_SQLITE=true only if you accept data loss." >&2
    exit 1
fi

mkdir -p \
    /var/www/html/bootstrap/cache \
    /var/www/html/database \
    /var/www/html/storage/app/public \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs

if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    DB_PATH="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    mkdir -p "$(dirname "$DB_PATH")"
    touch "$DB_PATH"
fi

chown -R www-data:www-data \
    /var/www/html/bootstrap/cache \
    /var/www/html/database \
    /var/www/html/storage

touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log
tail -n 0 -F /var/www/html/storage/logs/laravel.log >&2 &

if [ -z "${APP_KEY:-}" ] && [ "${APP_ENV:-production}" = "production" ]; then
    echo "APP_KEY is not set. Set a fixed APP_KEY in Railway before starting production." >&2
    echo "A temporary APP_KEY changes on every deploy and invalidates encrypted sessions/cookies." >&2
    exit 1
fi

if [ -z "${APP_KEY:-}" ]; then
    echo "APP_KEY is not set. Generating a temporary key for this container." >&2
    export APP_KEY="$(php artisan key:generate --show)"
fi

php artisan migrate --force
if [ "${RUN_DATABASE_SEEDER:-false}" = "true" ]; then
    php artisan db:seed --force
fi
php artisan storage:link || true

exec "$@"
