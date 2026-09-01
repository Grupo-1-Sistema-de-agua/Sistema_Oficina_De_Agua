FROM php:8.2-apache

# Extensiones que CodeIgniter 4 necesita (intl, mysqli) + libs para instalarlas
RUN apt-get update && apt-get install -y --no-install-recommends \
        libicu-dev \
        libzip-dev \
        unzip \
        git \
    && docker-php-ext-install intl mysqli pdo pdo_mysql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Composer (binario oficial, sin necesidad de instalarlo con curl)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache debe apuntar a /public, no a la raiz del proyecto (asi lo exige CI4)
COPY docker/app/000-default.conf /etc/apache2/sites-available/000-default.conf

COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]