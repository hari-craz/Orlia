# Redeploy Orlia on Portainer

## Steps to fix the database issue:

1. **In Portainer Web UI:**
   - Go to your Orlia stack
   - Click "Stop" 
   - Click "Remove" and CHECK the box "Remove associated volumes"
   
2. **Or via SSH to your Portainer host:**
   ```bash
   cd /path/to/Orlia
   git pull
   docker-compose down -v
   docker-compose build --no-cache
   docker-compose up -d
   ```

3. **Or manually remove the volume:**
   ```bash
   docker volume rm orlia_orlia-db-data
   ```

## Why this is needed:
The init.sql only runs when the database volume is empty. Since you're updating the schema, you need to remove the old volume so the new schema can be initialized.

## Verify it worked:
Visit: `http://your-server:8095/test_db.php`

You should see all three tables listed: `events`, `groupevents`, `login`
