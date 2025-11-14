# Use the Ubuntu 24.04 as the base image
FROM public.ecr.aws/docker/library/ubuntu:24.04


# Set environment variables
ENV LANG=C.UTF-8
ENV LC_ALL=C.UTF-8
ENV TZ=Asia/Manila
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/composer

# --- Composer Installation ---
COPY --from=public.ecr.aws/docker/library/composer:latest /usr/bin/composer /usr/bin/composer

# --- System and PHP Installation ---
RUN apt update \
    # Install necessary system dependencies and the PHP PPA
    && apt -y install --no-install-recommends tzdata software-properties-common lsb-release curl ca-certificates \
    && add-apt-repository ppa:ondrej/php -y \
    && apt update \
    # Install PHP 8.4 and required extensions (including MySQL for Filament/Laravel)
    && apt -y install --no-install-recommends \
        git \
        libicu-dev \
        libonig-dev \
        libpng-dev \
        libpq-dev \
        libxml2-dev \
        libzip-dev \
        unzip \
        zip \
        php8.4-bcmath \
        php8.4-cli \
        php8.4-common \
        php8.4-curl \
        php8.4-fpm \
        php8.4-gd \
        php8.4-intl \
        php8.4-mbstring \
        php8.4-mysql \
        php8.4-xml \
        php8.4-zip \
    # Clean up APT and set Composer configurations
    && apt clean \
    && rm -rf /var/lib/apt/lists/* \
    && composer config -g process-timeout 3600 \
    && composer config -g repos.packagist composer https://packagist.org

# --- Application and Configuration Copy ---
# Create a directory for the app
RUN mkdir -p /var/www/bssc_backend_admin

# Copy the app files to the container
COPY . /var/www/bssc_backend_admin

# Copy PHP-FPM pool and master config files
COPY ./docker/php/php-fpm.conf /etc/php/8.4/fpm/
COPY ./docker/php/www.conf /etc/php/8.4/fpm/pool.d/

# Copy default PHP configuration files
COPY ./docker/php/php.ini.development /etc/php/8.4/fpm/php.ini
COPY ./docker/php/php.ini.development /etc/php/8.4/cli/php.ini

# Copy production config if in production mode
RUN if [ "$PHP_MODE" = "production" ]; then \
        cp /var/www/bssc_backend_admin/docker/php/php.ini.production /etc/php/8.4/fpm/php.ini && \
        cp /var/www/bssc_backend_admin/docker/php/php.ini.production /etc/php/8.4/cli/php.ini; \
    fi

# Set the working directory to the app directory
WORKDIR /var/www/bssc_backend_admin

# --- Dependency Install and Permissions ---
# Install application dependencies, set ownership/permissions, and optimize
RUN if [ "$PHP_MODE" = "production" ]; then \
        composer install -q -n --no-ansi --no-dev --no-scripts --no-progress --prefer-dist; \
    else \
        composer install -q -n --no-ansi --no-scripts --no-progress --prefer-dist; \
    fi \
 && mkdir -p /var/www/.cache /var/www/.local /var/www/.config \
 && chown -R www-data:www-data /var/www/bssc_backend_admin /var/www/.cache /var/www/.local /var/www/.config \
 && chmod -R 755 storage bootstrap/cache /var/www/.cache /var/www/.local /var/www/.config

# --- Entrypoint ---
EXPOSE 9000
# Start PHP-FPM Server
CMD [ "/usr/sbin/php-fpm8.4" ]