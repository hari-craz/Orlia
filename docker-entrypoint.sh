#!/bin/bash
set -e

# Wait for DB to be ready
echo "Waiting for database..."
for i in $(seq 1 30); do
    if php -r "
        \$c = @mysqli_connect(
            getenv('DB_HOST') ?: 'db',
            'root',
            getenv('DB_ROOT_PASSWORD') ?: 'orlia_root',
            null,
            3306
        );
        if (\$c) { echo 'OK'; mysqli_close(\$c); exit(0); }
        exit(1);
    " 2>/dev/null | grep -q OK; then
        echo "Database is ready!"
        break
    fi
    echo "  DB not ready yet ($i/30)..."
    sleep 2
done

# Ensure app user exists with correct permissions
DB_NAME="${DB_NAME:-orlia}"
DB_USER="${DB_USER:-orlia}"
DB_PASSWORD="${DB_PASSWORD:-orlia}"
DB_ROOT_PASSWORD="${DB_ROOT_PASSWORD:-orlia_root}"

echo "Ensuring database user '${DB_USER}' exists..."
php -r "
    \$c = mysqli_connect(getenv('DB_HOST') ?: 'db', 'root', '${DB_ROOT_PASSWORD}', null, 3306);
    if (\$c) {
        mysqli_query(\$c, \"CREATE DATABASE IF NOT EXISTS \\\`${DB_NAME}\\\`\");
        mysqli_query(\$c, \"CREATE USER IF NOT EXISTS '${DB_USER}'@'%' IDENTIFIED BY '${DB_PASSWORD}'\");
        mysqli_query(\$c, \"GRANT ALL PRIVILEGES ON \\\`${DB_NAME}\\\`.* TO '${DB_USER}'@'%'\");
        mysqli_query(\$c, 'FLUSH PRIVILEGES');
        echo \"User '${DB_USER}' ready.\n\";
        mysqli_close(\$c);
    } else {
        echo \"Warning: Could not connect as root to create user. App may fail.\n\";
    }
"

# Start Apache
echo "Starting Apache..."
exec apache2-foreground
