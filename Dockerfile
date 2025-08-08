FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    bash \
    curl \
    git \
    zip \
    unzip \
    nginx \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    opcache

# Configure PHP
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory.ini
RUN echo "opcache.enable=1" > /usr/local/etc/php/conf.d/opcache.ini
RUN echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Create web directory
RUN mkdir -p /var/www/html

# Copy nginx configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Copy PHP-FPM configuration
COPY php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Copy supervisor configuration
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY ./public /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Create required directories
RUN mkdir -p /var/log/nginx /var/log/supervisor /run/nginx

# Expose port
EXPOSE 9000

# Start supervisor (which will manage nginx and php-fpm)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
