# Dockerfile pour Symfony sur Render
FROM php:8.4-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le code
COPY . /var/www/html

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer les dépendances
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Créer le répertoire var et permissions
RUN mkdir -p var && chown -R www-data:www-data var

# Rendre le script de démarrage exécutable
RUN chmod +x start.sh

# Configurer Apache pour Symfony
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite
COPY <<EOF /etc/apache2/sites-available/000-default.conf
<VirtualHost *:80>
    DocumentRoot /var/www/html/public
    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF

# Exposer le port 80
EXPOSE 80

# Variables d'environnement pour production
ENV APP_ENV=prod
ENV APP_DEBUG=false

# Commande de démarrage
CMD ["./start.sh"]
