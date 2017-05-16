#!/usr/bin/env bash

# make cache and storage directories writable
docker-compose exec code chmod -fR 755 storage
docker-compose exec code chmod -fR 755 cache

# cleanup Wordpress install
if [ -d "public_html/wp/wp-content" ]
then
    echo "Removing the wp/wp-content folder"
    docker-compose exec code rm -rf public_html/wp/wp-content
fi

if [ -f "public_html/wp/wp-config-sample.php" ]
then
    echo "Removing the wp/wp-config-sample.php file"
    docker-compose exec code rm -f public_html/wp/wp-config-sample.php
fi

if [ -f "public_html/wp/.htaccess" ]
then
    echo "Removing the wp/.htaccess file"
    docker-compose exec code rm -f public_html/wp/.htaccess
fi

# -------------------------------------------------------------
# generate new Laravel app key

docker-compose exec code php artisan key:generate;

# -------------------------------------------------------------
# generate new wordpress auth keys

docker-compose exec code php artisan wp:keys --file=.env

# -------------------------------------------------------------
# run artisan migration

docker-compose exec code php artisan migrate