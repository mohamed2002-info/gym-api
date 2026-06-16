# ---- Image PHP 8.3 avec serveur web intégré ----
FROM php:8.3-cli

# Dépendances système + extensions PHP (dont pgsql pour PostgreSQL)
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Installer les dépendances PHP (cache optimisé)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copier le reste du projet
COPY . .
RUN composer dump-autoload --optimize

# Script de démarrage : migrations + seed + serveur
COPY render-start.sh /render-start.sh
RUN chmod +x /render-start.sh

# Render fournit le port via $PORT
EXPOSE 8000

CMD ["/render-start.sh"]
