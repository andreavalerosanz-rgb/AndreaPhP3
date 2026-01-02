FROM php:8.2-apache

# 1. Instalar dependencias del sistema necesarias para Composer y Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# 2. Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# 3. INSTALAR COMPOSER (Copiando el binario oficial)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Configurar el DocumentRoot a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/htdocs!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html