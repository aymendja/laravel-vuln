# The project requires PHP 8.4 or newer (see composer.lock)
FROM php:8.4-cli

# System libraries + PHP extensions needed by Laravel
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip gd bcmath

# Install Composer (PHP package manager)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

# Copy the project, install the dependencies, create .env and the app key
COPY . .
RUN composer install --no-interaction \
    && cp .env.example .env \
    && php artisan key:generate

EXPOSE 8000

# On every start: create the tables + demo admin user, then run the server.
# (--no-reload makes the server use the database settings from docker-compose.yaml)
CMD ["sh", "-c", "php artisan migrate --force --seed && php artisan serve --host=0.0.0.0 --port=8000 --no-reload"]
