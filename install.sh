#!/usr/bin/env bash

# make cache and storage directories writable
chmod -fR 755 storage
chmod -fR 755 cache

# cleanup Wordpress install
if [ -d "public_html/wp/wp-content" ]
then
    echo "Removing the wp/wp-content folder"
    rm -rf public_html/wp/wp-content
fi

if [ -f "public_html/wp/wp-config-sample.php" ]
then
    echo "Removing the wp/wp-config-sample.php file"
    rm -f public_html/wp/wp-config-sample.php
fi

if [ -f "public_html/wp/.htaccess" ]
then
    echo "Removing the wp/.htaccess file"
    rm -f public_html/wp/.htaccess
fi

# -------------------------------------------------------------
# generate new Laravel app key

php artisan key:generate;

# -------------------------------------------------------------
# generate new wordpress auth keys

php artisan wp:keys --file=.env

# -------------------------------------------------------------
# run artisan migration

php artisan migrate