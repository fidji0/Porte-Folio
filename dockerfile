# Utilisez l'image officielle PHP avec Apache
FROM php:8.1-apache

# Installez les dépendances nécessaires
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev unzip \
    libfreetype6-dev libjpeg62-turbo-dev libwebp-dev zlib1g-dev libmagickwand-dev

# Configurez et installez les extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install pdo pdo_mysql opcache intl zip calendar dom mbstring gd xsl

# Installez et activez l'extension Imagick
RUN pecl install imagick && docker-php-ext-enable imagick

# Installez les dépendances nécessaires pour l'extension Exif
RUN apt-get install -y --no-install-recommends libexif-dev

# Installez l'extension Exif
RUN docker-php-ext-install exif

# Exposez le port 80 pour Apache
EXPOSE 80
