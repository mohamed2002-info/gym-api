#!/usr/bin/env bash
# Script de démarrage sur Render.
set -e

# Générer la clé d'app si absente (au cas où APP_KEY n'est pas définie)
php artisan key:generate --force || true

# Mettre en cache la config pour la production
php artisan config:clear

# Appliquer les migrations + données de démo (sans interaction)
php artisan migrate --force --seed || php artisan migrate --force

# Lancer le serveur sur le port fourni par Render
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
