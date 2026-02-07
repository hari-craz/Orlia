# Deploy Orlia on Portainer (TrueNAS)

## Quick Deploy (Using Portainer Web UI)

### Step 1: Remove Old Stack (If Exists)
1. Open Portainer: `http://your-truenas-ip:10010`
2. Go to **Stacks** from left menu
3. If "orlia" stack exists:
   - Click on it
   - Click **Stop this stack**
   - Click **Remove this stack**
   - ✅ **CHECK** "Remove associated volumes"
   - Confirm removal

### Step 2: Deploy New Stack from GitHub

1. Click **+ Add stack**
2. **Name**: `orlia`
3. **Build method**: Select **Repository**
4. Fill in repository details:
   - **Repository URL**: `https://github.com/hari-craz/Orlia`
   - **Repository reference**: `refs/heads/master`
   - **Compose path**: `docker-compose.yml`
5. Click **Deploy the stack**

### Step 3: Wait and Verify

1. Wait 30-45 seconds for containers to start and database to initialize
2. Check containers are running:
   - `orlia-web-25` - Running
   - `orlia-db-25` - Healthy
   - `orlia-phpmyadmin-25` - Running

3. **Test the deployment**:
   - Database test: `http://your-truenas-ip:10010/test_db.php`
   - Should show: `"status": "success"` with 3 tables
   - Main site: `http://your-truenas-ip:10010/`
   - phpMyAdmin: `http://your-truenas-ip:10011/`

---

## Method 2: SSH Deployment (Advanced)

If you have SSH access to your TrueNAS server:

```bash
# SSH into TrueNAS
ssh root@your-truenas-ip

# Navigate to apps directory (adjust path as needed)
cd /mnt/your-pool/apps

# Clone or update repository
git clone https://github.com/hari-craz/Orlia.git
# OR if already cloned:
cd Orlia && git pull

# Stop and remove old containers and volumes
docker-compose down -v

# Remove any orphaned volumes
docker volume rm orlia_orlia-db-data 2>/dev/null || true

# Build fresh images
docker-compose build --no-cache

# Start everything
docker-compose up -d

# Wait for database initialization
echo "Waiting 30 seconds for database..."
sleep 30

# Test the connection
curl http://localhost:10010/test_db.php
```

---

## Troubleshooting

### Tables don't exist after deployment

The database initialization only runs when the volume is empty. If you're redeploying:

```bash
# Method 1: Import SQL directly
docker exec -it orlia-db-25 mariadb -uroot -prootpassword orlia < /docker-entrypoint-initdb.d/init.sql

# Method 2: Run from inside container
docker exec -it orlia-db-25 mariadb -uroot -prootpassword orlia
SOURCE /docker-entrypoint-initdb.d/init.sql;
exit;

# Method 3: Via phpMyAdmin
# Visit http://your-ip:10011
# Login: root / rootpassword
# Select 'orlia' database
# Click SQL tab
# Import the init.sql file
```

### Check container logs

```bash
# Database logs
docker logs orlia-db-25

# Web server logs
docker logs orlia-web-25

# phpMyAdmin logs
docker logs orlia-phpmyadmin-25
```

### Verify tables exist

```bash
docker exec -it orlia-db-25 mariadb -uroot -prootpassword -e "USE orlia; SHOW TABLES;"
```

Should show:
- events
- groupevents
- login

### Port conflicts

If ports 10010 or 10011 are already in use, modify `docker-compose.yml`:

```yaml
services:
  web:
    ports:
      - "10010:80"  # Change 10010 to another port like 10012
  phpmyadmin:
    ports:
      - "10011:80"  # Change 10011 to another port like 10013
```

---

## Accessing the Application

Once deployed:

- **Main Website**: `http://your-truenas-ip:10010/`
- **Event Registration**: `http://your-truenas-ip:10010/register.php`
- **Group Registration**: `http://your-truenas-ip:10010/groupregister.php`
- **Admin Dashboard**: `http://your-truenas-ip:10010/dashboard.php`
- **phpMyAdmin**: `http://your-truenas-ip:10011/`

---

## Database Credentials

- **Database Host**: `db` (internal docker network)
- **Database Name**: `orlia`
- **Username**: `root`
- **Password**: `rootpassword`

**⚠️ IMPORTANT**: Change the default password in production!

Edit `docker-compose.yml`:
```yaml
db:
  environment:
    MYSQL_ROOT_PASSWORD: your-secure-password  # Change this!
```

And update in the web service:
```yaml
web:
  environment:
    DB_PASSWORD: your-secure-password  # Match the above!
```

---

## Why Volume Removal is Critical

The MariaDB init scripts (`init.sql`) **ONLY execute when the data directory is empty**. If you're updating the database schema:

1. You MUST remove the volume first
2. Or manually import the SQL after deployment
3. Otherwise, old schema/data persists

This is why "Remove associated volumes" must be checked when redeploying!
