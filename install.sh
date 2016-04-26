#!/usr/bin/env bash

tools=(vagrant npm sass compass gulp ruby gem compass bower)
# for each tool, make sure it's available to the current user
for i in "${tools[@]}"; do
	command -v ${i} >/dev/null 2>&1 || { echo "${i} not installed, aborting!" >&2; exit 1;}
done

# install npm libraries
echo "Installing Node dependencies!"
npm install &>/dev/null

# install bower dependencies
echo "Installing Bower dependencies!"
bower install &>/dev/null

# install bower dependencies
echo "Running gulp for the first time!"
gulp &>/dev/null

#download composer.phar for running composer commands
 if [ ! -f "composer.phar" ]
    then
        echo "Downloading composer.phar"
        wget https://getcomposer.org/composer.phar
    else
        php composer.phar self-update
    fi

#download c3.php for running Codeception remote coverage
 if [ ! -f "c3.php" ]
    then
        echo "Downloading c3.php"
        wget https://raw.github.com/Codeception/c3/2.0/c3.php
    fi

# download codecept.phar for running tests
 if [ ! -f "codecept.phar" ]
    then
        echo "Downloading codecept.phar"
        wget http://codeception.com/codecept.phar
    else
        php codecept.phar self-update
    fi

# make .env if not already created
if [ ! -f ".env" ]
    then
    cp .env.example .env
    echo ".env was created from example file"
    fi


# cleanup Wordpress install
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

# do vagrant up and ssh into the box to install composer dependencies
if [ -d ".vagrant" ]
    then
        vagrant destroy -f
    fi
vagrant up
vagrant ssh -c "cd /var/www; php composer.phar install;"
# generate new Laravel app key
vagrant ssh -c "cd /var/www; php artisan key:generate;"

# generate new wordpress auth keys
vagrant ssh -c "cd /var/www; php artisan wp:keys --file=.env;"

#fin