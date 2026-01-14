# Pre-Deployment Checklist for Word Game

## Before You Start

### 1. Server Requirements
- [ ] Hostinger VPS with Ubuntu 24.04 LTS
- [ ] Minimum 2GB RAM (4GB recommended)
- [ ] At least 20GB disk space
- [ ] Root or sudo access
- [ ] SSH access configured

### 2. Domain & DNS (Optional but Recommended)
- [ ] Domain name purchased
- [ ] DNS A record pointing to server IP
- [ ] DNS propagation completed (check with `nslookup yourdomain.com`)

### 3. Accounts & Credentials
- [ ] Hostinger account login details
- [ ] Server root/sudo password
- [ ] Email account for SMTP (if using Hostinger email)
- [ ] Strong password for database user (generate one)
- [ ] Strong password for admin user (generate one)

---

## Application Preparation

### 1. Test Locally First
- [ ] All games work (Crossword, Word Search, Wordle)
- [ ] Admin panel accessible
- [ ] Score export to Excel works
- [ ] Reset scores functionality works
- [ ] Leaderboard updates in real-time
- [ ] No console errors in browser

### 2. Update Configuration Files
- [ ] Review `config/app.php` - timezone set to 'Asia/Manila'
- [ ] Review `config/database.php` - MySQL configured
- [ ] Review `config/session.php` - database driver set
- [ ] Review `config/cache.php` - database cache configured

### 3. Prepare Assets
- [ ] Run `npm run build` locally to test
- [ ] Ensure all images are in `public/images/`
- [ ] Favicon files present (favicon.ico, favicon-16.png, favicon-32.png, apple-touch-icon.png)

### 4. Database
- [ ] Export current database schema: `php artisan schema:dump`
- [ ] Document any seed data needed
- [ ] List admin users to create

---

## Security Preparation

### 1. Passwords to Generate
Generate strong passwords for:
- [ ] Database user password
- [ ] Admin user password
- [ ] Email account password (if creating new)

Use a password generator (20+ characters, mixed case, numbers, symbols):
```bash
openssl rand -base64 32
```

### 2. Environment Variables
- [ ] Review `.env.example` and note what needs changing
- [ ] Prepare production APP_URL
- [ ] Prepare database credentials
- [ ] Prepare email credentials

### 3. SSL Certificate
- [ ] Decide: using SSL? (Recommended: YES)
- [ ] If yes, ensure domain DNS is pointing to server before SSL setup

---

## Files to Upload

### Required Files (all application files)
- [ ] All PHP files (`app/`, `routes/`, `config/`, etc.)
- [ ] `composer.json` and `composer.lock`
- [ ] `package.json` and `package-lock.json`
- [ ] `resources/` directory (views, CSS, JS)
- [ ] `public/` directory (images, favicons)
- [ ] `database/migrations/` directory
- [ ] `.env.example` file

### Files NOT to Upload
- [ ] `.env` file (create new on server)
- [ ] `/node_modules/` directory (install on server)
- [ ] `/vendor/` directory (install on server)
- [ ] `/storage/` directory (will be created)
- [ ] `/public/build/` directory (build on server)

---

## Information to Collect

### Server Information
```
Server IP Address: _________________
SSH Port: _________ (usually 22)
SSH Username: _________________
Domain Name: _________________
```

### Database Information
```
Database Name: word_game
Database User: wordgame_user
Database Password: _________________
```

### Email Configuration
```
SMTP Host: smtp.hostinger.com
SMTP Port: 587
Email Address: _________________
Email Password: _________________
```

### Admin User
```
Admin Name: _________________
Admin Email: _________________
Admin Password: _________________
```

---

## Quick Reference Commands

### Connect to Server
```bash
ssh root@your-server-ip
# or
ssh username@your-server-ip
```

### Test Database Connection
```bash
mysql -u wordgame_user -p word_game
```

### Check Service Status
```bash
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status mysql
sudo supervisorctl status
```

### View Logs
```bash
tail -f /var/www/word-game/storage/logs/laravel.log
tail -f /var/log/nginx/error.log
```

---

## Post-Deployment Tests

After deployment, test these:

### Basic Functionality
- [ ] Homepage loads correctly
- [ ] Can register new user
- [ ] Can login with regular user
- [ ] Can login with admin user
- [ ] Games are playable:
  - [ ] Crossword
  - [ ] Word Search
  - [ ] Wordle

### Admin Features
- [ ] Admin dashboard accessible
- [ ] Can view all scores
- [ ] Can filter scores by game type and level
- [ ] Can export scores to Excel
- [ ] Excel file contains filter information
- [ ] Can reset all scores (test carefully!)
- [ ] Can create/edit/delete levels
- [ ] Can create/edit/delete words

### Real-time Features
- [ ] Leaderboard updates without refresh
- [ ] WebSocket connection working (check browser console)
- [ ] Recent scores appear in real-time

### Performance
- [ ] Page load time < 3 seconds
- [ ] No 404 errors for assets
- [ ] Images load correctly
- [ ] Favicon displays in browser tab

### Security
- [ ] HTTPS working (if configured)
- [ ] Cannot access `/storage/` directly
- [ ] Cannot access `.env` file via browser
- [ ] Admin routes protected (redirect to login)

---

## Emergency Rollback Plan

If deployment fails:

### 1. Check Logs First
```bash
tail -100 /var/www/word-game/storage/logs/laravel.log
tail -100 /var/log/nginx/error.log
```

### 2. Common Fixes
```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/word-game/storage
sudo chmod -R 775 /var/www/word-game/storage

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo supervisorctl restart all
```

### 3. Restore from Backup (if you have one)
```bash
# Restore database
mysql -u wordgame_user -p word_game < /var/backups/word-game/db_TIMESTAMP.sql

# Restore files
tar -xzf /var/backups/word-game/storage_TIMESTAMP.tar.gz -C /
```

---

## Support Contacts

**Hostinger Support:**
- Live Chat: Available in Hostinger control panel
- Email: support@hostinger.com
- Knowledge Base: https://www.hostinger.com/tutorials

**Laravel Community:**
- Documentation: https://laravel.com/docs
- Forum: https://laracasts.com/discuss
- Discord: https://discord.gg/laravel

---

## Timeline Estimate

**Total estimated time: 2-4 hours**

- Server setup: 30-60 minutes
- Software installation: 30-45 minutes
- Application deployment: 30-60 minutes
- SSL setup: 15-30 minutes
- Testing & troubleshooting: 30-60 minutes

*Times vary based on internet speed, server performance, and experience level.*

---

## Final Checklist Before Going Live

- [ ] All tests passed
- [ ] Backup created
- [ ] SSL certificate installed and working
- [ ] Firewall configured
- [ ] Queue workers running
- [ ] Reverb WebSocket server running
- [ ] Admin user created and tested
- [ ] Email notifications working
- [ ] Performance acceptable
- [ ] Security measures in place
- [ ] Monitoring/logging configured
- [ ] Documentation updated with server details

---

**Deployment Date**: __________
**Deployed By**: __________
**Server IP**: __________
**Domain**: __________
**Notes**: ___________________________
