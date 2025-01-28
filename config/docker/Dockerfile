FROM php:8.4-fpm

RUN apt-get update && apt-get install  --no-install-recommends -y \
    unzip \
    git \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install zip

# Copy Composer
COPY --from=composer/composer:lts /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www
