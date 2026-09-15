#!/bin/sh
set -e

# vendor/ vive en un volumen aparte (ver docker-compose.yml), asi que la
# primera vez que alguien levanta el proyecto hay que instalar dependencias.
if [ ! -f vendor/autoload.php ]; then
    echo "Instalando dependencias con Composer (primera vez, puede tardar unos minutos)..."
    composer install --no-interaction --optimize-autoloader
fi

exec "$@"