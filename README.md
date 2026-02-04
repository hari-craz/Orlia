# Orlia'26 - Cultural Event Management System

A PHP-based event management system for MKCE's Cultural Fest (Orlia 2026).

## Features

- **Solo & Group Event Registration** - Register for various cultural events
- **Multi-Admin Roles** - Super Admin, Event Admin, Co-Admin
- **Event Pass Generation** - Auto-generate QR-coded event passes
- **Photography Voting** - Integrated voting system
- **File Uploads** - Support for video, photo, and audio uploads
- **Real-time Validation** - Roll number and team name validation
- **Database Export/Backup** - Super admin database management

## Tech Stack

- **Backend**: PHP 8.2 with MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript, jQuery
- **UI Libraries**: SweetAlert2, Remix Icons, DataTables
- **Containerization**: Docker & Docker Compose

---

## 🐳 Docker Deployment (Portainer/TrueNAS)

### Prerequisites
- Docker & Docker Compose installed
- Portainer (optional for GUI management)
- Ports 8096 and 8095 available

### Quick Start

1. **Clone the repository**:
   ```bash
   git clone https://github.com/hari-craz/Orlia.git
   cd Orlia
   ```

2. **Create environment file**:
   ```bash
   cp .env.example .env
   # Edit .env with your preferred passwords
   ```

3. **Start the containers**:
   ```bash
   docker-compose up -d --build
   ```

4. **Access the application**:
   - **Web App**: http://your-server-ip:8096
   - **phpMyAdmin**: http://your-server-ip:8095

### Default Credentials

| Role | Username | Password |
|------|----------|----------|
| Super Admin | superadmin | 12345 |
| Admin | admin | 123456@ |

---

## 📦 Portainer Stack Deployment

### Method 1: Using Git Repository

1. Go to **Stacks** → **Add Stack**
2. Select **Repository**
3. Enter: `https://github.com/hari-craz/Orlia.git`
4. Set compose path: `docker-compose.yml`
5. Deploy the stack

### Method 2: Using Web Editor

1. Go to **Stacks** → **Add Stack**
2. Paste the content of `docker-compose.yml`
3. Set **Environment Variables**:
   - `MYSQL_ROOT_PASSWORD=root_secure_2026`
   - `MYSQL_PASSWORD=orlia_secure_pass_2026`
4. Deploy the stack

---

## 🚀 TrueNAS Scale Deployment

### Using Apps/Custom App

1. Go to **Apps** → **Discover Apps** → **Custom App**
2. Use the following settings:

   **Container Image**: 
   - Repository: `ghcr.io/hari-craz/orlia` (if published)
   - Or build from Dockerfile

   **Port Mappings**:
   | Container Port | Host Port |
   |---------------|-----------|
   | 80 | 8096 |

   **Environment Variables**:
   | Variable | Value |
   |----------|-------|
   | DB_HOST | orlia-db |
   | DB_USER | orlia_user |
   | DB_PASSWORD | orlia_secure_pass_2026 |
   | DB_NAME | orlia |

3. Set up persistent storage for:
   - `/var/www/html/uploads`
   - MySQL data volume

---

## 📁 Project Structure

```
Orlia/
├── index.php              # Main landing page
├── login.php              # Admin login
├── register.php           # Solo event registration
├── teamRegister.php       # Group event registration
├── backend.php            # API endpoints
├── db.php                 # Database connection
├── superAdmin.php         # Super admin dashboard
├── eventAdmin.php         # Event admin dashboard
├── adminDashboard.php     # Co-admin dashboard
├── docker-compose.yml     # Docker configuration
├── Dockerfile             # PHP container build
├── assets/
│   ├── Schema/orlia.sql   # Database schema
│   ├── styles/            # CSS files
│   ├── script/            # JavaScript files
│   └── images/            # Static images
├── includes/
│   ├── auth.php           # Authentication
│   └── sidebar.php        # Admin sidebar
└── uploads/               # User uploaded files
    ├── photos/
    ├── videos/
    └── songs/
```

---

## 🔧 Local Development

### Using XAMPP/WAMP

1. Place files in `htdocs` folder
2. Import `assets/Schema/orlia.sql` into MySQL
3. Update `db.php` with local credentials:
   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "orlia";
   ```
4. Access: http://localhost/Orlia

### Using Docker Locally

```bash
docker-compose up --build
```

---

## 🔐 Admin Roles

| Role | Access Level |
|------|--------------|
| **Super Admin (2)** | Full system access, manage admins, export data |
| **Event Admin (1)** | Manage specific event participants, feedback |
| **Co-Admin (0)** | View participants, limited management |

---

## 📊 Database Tables

- `events` - Event definitions
- `soloevents` - Solo registrations
- `groupevents` - Team registrations
- `users` - Admin accounts
- `photography` - Voting records
- `feedback` - Event feedback

---

## 🛡️ Security Notes

- Change default passwords before production deployment
- Use HTTPS in production (configure with reverse proxy)
- Consider adding rate limiting for API endpoints
- Passwords should be hashed in future versions

---

## 📞 Contact

- **Email**: fineartsclub2k25@gmail.com
- **Location**: MKCE Campus, Karur

---

## License

© 2026 Syraa Groups. All Rights Reserved.
