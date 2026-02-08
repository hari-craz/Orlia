#!/bin/bash

# Wait for DB port to be reachable (no credentials needed)
echo "Waiting for database port..."
for i in $(seq 1 30); do
    if php -r "@fsockopen(getenv('DB_HOST') ?: 'db', 3306, \$e, \$m, 2) ? exit(0) : exit(1);" 2>/dev/null; then
        echo "Database port is open!"
        break
    fi
    echo "  DB not ready yet ($i/30)..."
    sleep 2
done

# Try to ensure app user exists (best-effort, won't crash if it fails)
DB_HOST="${DB_HOST:-db}"
DB_NAME="${DB_NAME:-orlia}"
DB_USER="${DB_USER:-orlia}"
DB_PASSWORD="${DB_PASSWORD:-orlia}"
DB_ROOT_PASSWORD="${DB_ROOT_PASSWORD:-orlia_root}"

echo "Ensuring database user '${DB_USER}' exists..."
php -r "
    mysqli_report(MYSQLI_REPORT_OFF);
    \$c = @mysqli_connect('${DB_HOST}', 'root', '${DB_ROOT_PASSWORD}', null, 3306);
    if (\$c) {
        @mysqli_query(\$c, \"CREATE DATABASE IF NOT EXISTS \\\`${DB_NAME}\\\`\");
        @mysqli_query(\$c, \"CREATE USER IF NOT EXISTS '${DB_USER}'@'%' IDENTIFIED BY '${DB_PASSWORD}'\");
        @mysqli_query(\$c, \"GRANT ALL PRIVILEGES ON \\\`${DB_NAME}\\\`.* TO '${DB_USER}'@'%'\");
        @mysqli_query(\$c, 'FLUSH PRIVILEGES');
        echo \"User '${DB_USER}' configured.\n\";
        mysqli_close(\$c);
    } else {
        echo \"Note: Could not connect as root (\" . @mysqli_connect_error() . \"). Skipping user setup.\n\";
    }
" 2>/dev/null || echo "User setup skipped."

# Start Apache regardless
echo "Starting Apache..."
exec apache2-foreground
