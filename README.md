# PHP 8.4 Docker with Nginx

A simple PHP 8.4 application running with Nginx on port 9000.

## Features

- PHP 8.4 FPM
- Nginx web server
- Supervisor for process management
- OPcache enabled
- MySQLi and PDO extensions
- Security headers configured

## Quick Start

1. **Build and run the container:**
   ```bash
   docker-compose up -d --build
   ```

2. **Access the application:**
   - Main page: http://localhost:9000
   - PHP info: http://localhost:9000/index.php

3. **Stop the container:**
   ```bash
   docker-compose down
   ```

## Project Structure

```
php8.4-docker/
├── Dockerfile              # Main Docker image configuration
├── docker-compose.yml      # Docker Compose configuration
├── nginx.conf              # Nginx configuration
├── php-fpm.conf           # PHP-FPM configuration
├── supervisord.conf        # Supervisor configuration
├── public/                 # Web root directory
│   ├── index.php          # PHP info page
│   └── test.php           # Test page with extensions check
└── README.md              # This file
```

## Configuration Details

- **Nginx**: Listens on port 9000
- **PHP-FPM**: Runs on internal port 9001
- **Supervisor**: Manages both Nginx and PHP-FPM processes
- **PHP Extensions**: mysqli, pdo, pdo_mysql, opcache
- **Memory Limit**: 512M
- **OPcache**: Enabled for better performance

## Development

To modify the application, edit files in the `public/` directory. Changes will be reflected immediately due to the volume mount.

## Logs

View container logs:
```bash
docker-compose logs -f
```

## Customization

- Edit `nginx.conf` for Nginx settings
- Edit `php-fpm.conf` for PHP-FPM settings
- Edit `Dockerfile` to add more PHP extensions
- Edit `supervisord.conf` to manage additional services
