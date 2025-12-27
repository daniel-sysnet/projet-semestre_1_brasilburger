FROM php:8.2-apache

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

# VirtualHost Symfony
RUN printf "<VirtualHost *:%s>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>\n" "${PORT}" > /etc/apache2/sites-available/000-default.conf

# Adapter Apache au port Render
RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/ports.conf

ENV APP_ENV=prod
ENV APP_DEBUG=0

EXPOSE ${PORT}

CMD ["apache2-foreground"]
