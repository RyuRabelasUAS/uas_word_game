# Laravel Word Game - Hostinger Ubuntu 24.04 Deployment Guide

## Prerequisites

- Hostinger VPS with Ubuntu 24.04 LTS
- SSH access to your server
- Domain name (optional, but recommended)
- Basic knowledge of Linux terminal

---

## Part 1: Server Setup & Software Installation

### Step 1: Connect to Your Server via SSH

```bash
ssh root@your-server-ip
# Or if using a non-root user:
ssh username@your-server-ip
```

### Step 2: Update System Packages

```bash
sudo apt update && sudo apt upgrade -y
```

### Step 3: Install Required Software

#### Install Nginx Web Server
```bash
sudo apt install nginx -y
sudo systemctl enable nginx
sudo systemctl start nginx
```

#### Install PHP 8.2 and Required Extensions
```bash
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

sudo apt install php8.2 php8.2-fpm php8.2-cli php8.2-common php8.2-mysql \
php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath \
php8.2-redis php8.2-intl -y
```

#### Install MySQL 8.0
```bash
sudo apt install mysql-server -y
sudo systemctl enable mysql
sudo systemctl start mysql

# Secure MySQL installation
sudo mysql_secure_installation
```

#### Install Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

#### Install Node.js & NPM (v20.x LTS)
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install nodejs -y
```

#### Install Git
```bash
sudo apt install git -y
```

#### Install Supervisor (for Queue Workers & Reverb)
```bash
sudo apt install supervisor -y
sudo systemctl enable supervisor
sudo systemctl start supervisor
```

---

## Part 2: Database Setup

### Create Database and User

```bash
sudo mysql -u root -p
```

Inside MySQL console:
```sql
CREATE DATABASE word_game CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'wordgame_user'@'localhost' IDENTIFIED BY 'your_strong_password_here';
GRANT ALL PRIVILEGES ON word_game.* TO 'wordgame_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## Part 3: Deploy Application Files

### Step 1: Create Application Directory

```bash
sudo mkdir -p /var/www/word-game
sudo chown -R $USER:$USER /var/www/word-game
cd /var/www/word-game
```

### Step 2: Upload Your Application Files

**Option A: Using Git (Recommended)**
```bash
# Initialize git repo locally on your dev machine first
cd /path/to/your/local/word-game
git init
git add .
git commit -m "Initial deployment"

# Push to GitHub/GitLab/Bitbucket, then on server:
cd /var/www/word-game
git clone https://github.com/yourusername/word-game.git .
```

**Option B: Using SCP/SFTP**
```bash
# From your local machine (Windows PowerShell):
scp -r "C:\Users\User\Desktop\Personal Projects\2025\test-crossword\word-game\*" username@your-server-ip:/var/www/word-game/
```

**Option C: Using Hostinger File Manager**
- Upload all files via Hostinger's control panel File Manager

### Step 3: Set Proper Permissions

```bash
cd /var/www/word-game
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

---

## Part 4: Configure Environment

### Step 1: Create Production .env File

```bash
cd /var/www/word-game
cp .env.example .env
nano .env
```

### Step 2: Update .env with Production Settings

```env
APP_NAME="Word Game"
APP_ENV=production
APP_KEY=  # Will generate this later
APP_DEBUG=false
APP_URL=https://yourdomain.com  # Or http://your-server-ip

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=word_game
DB_USERNAME=wordgame_user
DB_PASSWORD=your_strong_password_here

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true  # Set to true if using HTTPS
SESSION_SAME_SITE=lax

BROADCAST_CONNECTION=reverb
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# Reverb WebSocket Settings
REVERB_APP_ID=118830
REVERB_APP_KEY=p9y2cr4htcggjeouesr2
REVERB_APP_SECRET=cnpexdc2nllvxe76xx7r
REVERB_HOST=yourdomain.com  # Or your-server-ip
REVERB_PORT=8080
REVERB_SCHEME=https  # Use https if you have SSL

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

Press `Ctrl+O` to save, then `Ctrl+X` to exit.

---

## Part 5: Install Dependencies & Build Assets

### Step 1: Install PHP Dependencies

```bash
cd /var/www/word-game
composer install --optimize-autoloader --no-dev
```

### Step 2: Generate Application Key

```bash
php artisan key:generate
```

### Step 3: Install Node Dependencies & Build Assets

```bash
npm install
npm run build
```

### Step 4: Run Database Migrations

```bash
php artisan migrate --force
```

### Step 5: Optimize Application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Part 6: Configure Nginx

### Create Nginx Site Configuration

```bash
sudo nano /etc/nginx/sites-available/word-game
```

Paste the following configuration:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;  # Change this to your domain or server IP
    root /var/www/word-game/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # Main location block
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM configuration
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Deny access to hidden files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # WebSocket proxy for Laravel Reverb
    location /app {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "Upgrade";
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

### Enable the Site

```bash
sudo ln -s /etc/nginx/sites-available/word-game /etc/nginx/sites-enabled/
sudo nginx -t  # Test configuration
sudo systemctl reload nginx
```

---

## Part 7: Configure Supervisor for Queue Workers & Reverb

### Create Queue Worker Configuration

```bash
sudo nano /etc/supervisor/conf.d/word-game-worker.conf
```

Paste:
```ini
[program:word-game-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/word-game/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/word-game/storage/logs/worker.log
stopwaitsecs=3600
```

### Create Reverb WebSocket Server Configuration

```bash
sudo nano /etc/supervisor/conf.d/word-game-reverb.conf
```

Paste:
```ini
[program:word-game-reverb]
process_name=%(program_name)s
command=php /var/www/word-game/artisan reverb:start --host=0.0.0.0 --port=8080
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/word-game/storage/logs/reverb.log
```

### Reload Supervisor

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all
```

Check status:
```bash
sudo supervisorctl status
```

---

## Part 8: SSL Certificate (Optional but Recommended)

### Install Certbot for Let's Encrypt SSL

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

Follow the prompts. Certbot will automatically configure SSL in your Nginx config.

After SSL is installed, update your `.env`:
```bash
nano /var/www/word-game/.env
```

Change:
```env
APP_URL=https://yourdomain.com
SESSION_SECURE_COOKIE=true
REVERB_SCHEME=https
VITE_REVERB_SCHEME=https
```

Then clear cache:
```bash
cd /var/www/word-game
php artisan config:clear
php artisan config:cache
```

---

## Part 9: Firewall Configuration

```bash
sudo ufw allow 22/tcp      # SSH
sudo ufw allow 80/tcp      # HTTP
sudo ufw allow 443/tcp     # HTTPS
sudo ufw enable
```

---

## Part 10: Create Admin User

### Option A: Via SSH
```bash
cd /var/www/word-game
php artisan tinker
```

Then in tinker:
```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@yourdomain.com';
$user->password = bcrypt('your_secure_password');
$user->is_admin = true;
$user->save();
exit;
```

### Option B: Via Database
```bash
mysql -u wordgame_user -p word_game
```

```sql
INSERT INTO users (name, email, password, is_admin, created_at, updated_at)
VALUES ('Admin', 'admin@yourdomain.com', '$2y$12$YourHashedPasswordHere', 1, NOW(), NOW());
```

---

## Part 11: Post-Deployment Checklist

- [ ] Application loads at `http://yourdomain.com`
- [ ] Admin login works at `http://yourdomain.com/login`
- [ ] Database connections working
- [ ] Games load and play correctly
- [ ] Leaderboard updates in real-time (WebSocket working)
- [ ] File uploads work (if applicable)
- [ ] Email sending works (test password reset)
- [ ] Queue workers running (`sudo supervisorctl status`)
- [ ] Reverb WebSocket server running
- [ ] SSL certificate valid and auto-renewal configured
- [ ] Logs are being written to `/var/www/word-game/storage/logs`

---

## Maintenance Commands

### Update Application
```bash
cd /var/www/word-game
git pull origin main  # If using Git
composer install --optimize-autoloader --no-dev
npm install && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo supervisorctl restart all
```

### View Logs
```bash
tail -f /var/www/word-game/storage/logs/laravel.log
tail -f /var/www/word-game/storage/logs/worker.log
tail -f /var/www/word-game/storage/logs/reverb.log
```

### Restart Services
```bash
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo supervisorctl restart all
```

### Clear Cache
```bash
cd /var/www/word-game
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## Troubleshooting

### Issue: 500 Internal Server Error
```bash
# Check logs
tail -50 /var/www/word-game/storage/logs/laravel.log
tail -50 /var/log/nginx/error.log

# Check permissions
sudo chown -R www-data:www-data /var/www/word-game/storage
sudo chmod -R 775 /var/www/word-game/storage
```

### Issue: Database Connection Failed
```bash
# Test database connection
mysql -u wordgame_user -p word_game

# Check .env database credentials
cat /var/www/word-game/.env | grep DB_
```

### Issue: WebSocket Not Working
```bash
# Check if Reverb is running
sudo supervisorctl status word-game-reverb

# Check Reverb logs
tail -50 /var/www/word-game/storage/logs/reverb.log

# Restart Reverb
sudo supervisorctl restart word-game-reverb
```

### Issue: Queue Jobs Not Processing
```bash
# Check worker status
sudo supervisorctl status word-game-worker

# Restart workers
sudo supervisorctl restart word-game-worker:*
```

---

## Security Best Practices

1. **Keep PHP & packages updated**: `sudo apt update && sudo apt upgrade`
2. **Use strong passwords**: For database, admin accounts, etc.
3. **Disable directory listing**: Already configured in Nginx
4. **Enable firewall**: UFW configuration above
5. **Regular backups**: Set up automated database & file backups
6. **Monitor logs**: Check `/var/www/word-game/storage/logs` regularly
7. **Use HTTPS**: Install SSL certificate
8. **Limit SSH access**: Use key-based authentication, disable root login
9. **Keep .env secure**: Permissions should be 600

```bash
chmod 600 /var/www/word-game/.env
```

---

## Backup Strategy

### Database Backup Script
```bash
sudo nano /usr/local/bin/backup-word-game.sh
```

```bash
#!/bin/bash
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/var/backups/word-game"
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u wordgame_user -p'your_password' word_game > $BACKUP_DIR/db_$TIMESTAMP.sql

# Backup uploaded files (if any)
tar -czf $BACKUP_DIR/storage_$TIMESTAMP.tar.gz /var/www/word-game/storage/app

# Keep only last 7 days of backups
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Backup completed: $TIMESTAMP"
```

```bash
sudo chmod +x /usr/local/bin/backup-word-game.sh
```

### Schedule with Cron
```bash
crontab -e
```

Add:
```
0 2 * * * /usr/local/bin/backup-word-game.sh >> /var/log/word-game-backup.log 2>&1
```

---

## Performance Optimization

### Enable OPcache
```bash
sudo nano /etc/php/8.2/fpm/php.ini
```

Find and set:
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

Restart PHP-FPM:
```bash
sudo systemctl restart php8.2-fpm
```

### Enable Nginx Gzip
Already enabled by default, but verify in `/etc/nginx/nginx.conf`:
```nginx
gzip on;
gzip_vary on;
gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/javascript application/xml+rss application/json;
```

---

## Support & Resources

- Laravel Documentation: https://laravel.com/docs
- Hostinger Support: https://www.hostinger.com/tutorials
- Ubuntu Documentation: https://help.ubuntu.com

---

**Deployment Date**: 2026-01-14
**Laravel Version**: 12.x
**PHP Version**: 8.2
**Server OS**: Ubuntu 24.04 LTS
