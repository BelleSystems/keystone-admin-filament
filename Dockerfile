FROM php:8.2-cli

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev && \
    docker-php-ext-install zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Laravel files
COPY . .

# Install PHP dependencies
RUN composer install
#RUN npm install

EXPOSE 8080

#CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
