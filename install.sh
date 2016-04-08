#!/usr/bin/env bash

# install npm libraries
command -v npm >/dev/null 2>&1 || { echo "NPM is not installed, aborting." >&2; exit 1; }
echo "Installing Node dependencies!"
npm install &>/dev/null


# install bower dependencies
command -v bower >/dev/null 2>&1 || { echo "Bower is not installed. Please install by runnning 'npm install -g bower'. Aborting" >&2; exit 1; }
echo "Installing Bower dependencies!"
bower install &>/dev/null


# install bower dependencies
command -v gulp >/dev/null 2>&1 || { echo "Gulp is not installed. Please install by runnning 'npm install -g gulp'. aborting" >&2; exit 1; }
echo "Running gulp for the first time!"
gulp &>/dev/null

#download composer.phar for running composer commands
 if [ ! -f "composer.phar" ]
    then
        echo "Downloading composer.phar"
        wget https://getcomposer.org/composer.phar
    fi

#download c3.php for running Codeception remote coverage
 if [ ! -f "c3.php" ]
    then
        echo "Downloading c3.php"
        wget https://raw.github.com/Codeception/c3/2.0/c3.php
    fi

# download codecept.phar for running tests
 if [ ! -f "composer.phar" ]
    then
        echo "Downloading codecept.phar"
        wget http://codeception.com/codecept.phar
    fi

# make .env if not already created
 if [ ! -f ".env" ]
 then
    cp .env.example .env
    echo ".env was created from example file"
 fi

# create puphet config if not already created
if [ ! -f "puphpet/config.yaml" ]
then
    cp puphpet/config.yaml.example puphpet/config.yaml
    echo "puphpet config.yaml file was created from example file"
fi

# cleanup wordpress instal
if [ -d "public_html/wp/wp-content" ]
then
    rm -rf public_html/wp/wp-content
fi

if [ -f "public_html/wp/wp-config-sample.php" ]
then
    rm -f public_html/wp/wp-config-sample.php
fi

if [ -f "public_html/wp/.htaccess" ]
then
    rm -f public_html/wp/.htaccess
fi

# generate the APP_KEY for laravel
echo "Generating Laravel App Key"
php artisan key:generate

# do vagrant up and ssh into the box to install composer dependencies
vagrant up
vagrant ssh -c "cd /var/www; php composer.phar install;"

#fin