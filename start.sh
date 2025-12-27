#!/bin/bash

# Attendre que la DB soit prête (optionnel, mais utile)
# sleep 10

# Exécuter les migrations
php bin/console doctrine:migrations:migrate --no-interaction

# Démarrer Apache
apache2-foreground