FROM php:8.4-apache

# Dépendances système
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Apache
RUN a2enmod rewrite
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Config Apache Symfony
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Code source
COPY . .

# Installer dépendances Symfony
RUN composer install --no-dev --optimize-autoloader

# Permissions Symfony
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var \
    && chmod -R 775 var

# Variables d’environnement
ENV APP_ENV=prod
ENV APP_DEBUG=0

EXPOSE 80

CMD ["apache2-foreground"]
