FROM php:8.3-fpm

# Install system packages needed to build PHP extensions.
# libpq-dev provides the PostgreSQL client headers; the others are
# common build tools and the unzip Composer needs.
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install the PHP extensions our app needs.
# pdo_pgsql = the PDO driver that lets PHP talk to PostgreSQL.
RUN docker-php-ext-install pdo pdo_pgsql zip

# Pull the Composer binary from its official image into ours.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html