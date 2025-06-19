FROM php:8.2-fpm

# Install Nginx and dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    libpq-dev \
    curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Copy Nginx configuration
COPY nginx.conf /etc/nginx/sites-enabled/default

# Test Nginx configuration
RUN nginx -t

# Set working directory
WORKDIR /app
COPY . /app

# Install PHP dependencies (if composer.json exists)
RUN composer install --no-dev --optimize-autoloader || true

# Ensure uploads directory is writable
RUN mkdir -p /app/uploads && chmod -R 777 /app/uploads

# Start Nginx and PHP-FPM in foreground
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]