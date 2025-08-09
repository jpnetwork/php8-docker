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

# Install Redis extension
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

# Configure PHP
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory.ini
RUN echo "date.timezone=Asia/Bangkok" > /usr/local/etc/php/conf.d/timezone.ini

# Opcache tuning
RUN { \
  echo "opcache.enable=1"; \
  echo "opcache.enable_cli=1"; \
  echo "opcache.memory_consumption=256"; \
  echo "opcache.interned_strings_buffer=16"; \
  echo "opcache.max_accelerated_files=20000"; \
  echo "opcache.validate_timestamps=0"; \
  echo "opcache.save_comments=1"; \
  echo "opcache.fast_shutdown=1"; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Configure Redis sessions
RUN echo "session.save_handler=redis" > /usr/local/etc/php/conf.d/redis-session.ini
RUN echo "session.save_path=\"tcp://192.168.99.7:6379\"" >> /usr/local/etc/php/conf.d/redis-session.ini

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
