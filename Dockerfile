FROM php:8.2-apache

# Install required PHP extensions and curl for health checks
RUN apt-get update && apt-get install -y curl && rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Create session directory
RUN mkdir -p /tmp/voting-sessions && chown -R www-data:www-data /tmp/voting-sessions

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
