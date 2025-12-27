#!/bin/bash

# Attendre que la DB soit prête (optionnel, mais utile)
# sleep 10

# Exécuter les migrations
php bin/console doctrine:migrations:migrate --no-interaction

# Nettoyer le cache
php bin/console cache:clear --env=prod

# Préchauffer le cache
php bin/console cache:warmup --env=prod

# Démarrer Apache
apache2-foreground