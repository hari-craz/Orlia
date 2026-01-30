# Deploy Orlia on Portainer - COMPLETE GUIDE

## Method 1: Using Portainer Web UI (EASIEST)

### Step 1: Remove the old stack
1. Open Portainer web interface
2. Go to **Stacks** from the left menu
3. Find your **Orlia** stack
4. Click on it, then click **Stop this stack**
5. After it stops, click **Remove this stack**
6. **IMPORTANT**: Check the box ✅ **"Remove associated volumes"**
7. Click confirm

### Step 2: Create new stack
1. Click **+ Add stack**
2. Name it: `orlia`
3. Build method: **Repository**
4. Repository URL: `https://github.com/hari-craz/Orlia`
5. Repository reference: `refs/heads/master`
6. Compose path: `docker-compose.yml`
7. Click **Deploy the stack**

### Step 3: Wait and verify
1. Wait 30 seconds for containers to start
2. Visit: `http://your-server:8095/test_db.php`
3. Should show: `"status": "success"` and 3 tables

---

## Method 2: Using SSH/Terminal (ADVANCED)

SSH into your Portainer host and run:

```bash
# Navigate to where you want to deploy
cd /opt/orlia  # or wherever you want

# Clone or pull the repo
git clone https://github.com/hari-craz/Orlia.git
cd Orlia

# OR if already cloned:
# git pull

# Stop and remove everything including volumes
docker-compose down -v

# Remove any orphaned volumes
docker volume rm orlia_orlia-db-data 2>/dev/null || true

# Build fresh images
docker-compose build --no-cache

# Start everything
docker-compose up -d

# Wait for database to initialize
echo "Waiting 30 seconds for database initialization..."
sleep 30

# Test the connection
curl http://localhost:8095/test_db.php
```

---

## Troubleshooting

### If tables still don't exist:
```bash
# Connect to database container
docker exec -it orlia-db mariadb -uroot -prootpassword orlia

# Then run these SQL commands:
SOURCE /docker-entrypoint-initdb.d/init.sql;
exit;
```

### Check logs:
```bash
docker logs orlia-db
docker logs orlia-web
```

### Verify tables exist:
```bash
docker exec -it orlia-db mariadb -uroot -prootpassword -e "USE orlia; SHOW TABLES;"
```

---

## Why the volume must be removed:
The `init.sql` ONLY runs when MariaDB starts with an **empty data directory**. If the volume already has old data, it won't re-initialize. That's why you must remove the volume first.
