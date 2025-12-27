# Image PHP stable recommandée pour Symfony
FROM php:8.2-apache

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Activer mod_rewrite pour Symfony
RUN a2enmod rewrite

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le code source
COPY . .

# Installer les dépendances PHP (prod uniquement)
RUN composer install --no-dev --optimize-autoloader

# Créer les dossiers nécessaires et corriger les permissions
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var \
    && chmod -R 775 var

# Configuration Apache pour Symfony
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN printf "<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>\n" > /etc/apache2/sites-available/000-default.conf

# Variables d'environnement
ENV APP_ENV=prod
ENV APP_DEBUG=0

# Exposer le port
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]
