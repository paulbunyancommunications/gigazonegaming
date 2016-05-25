#!/usr/bin/env bash

# if not already downloaded, get the parse_yaml.sh script for parsing yaml config files
if [ ! -f "parse_yaml.sh" ]
    then
        wget https://gist.githubusercontent.com/pkuczynski/8665367/raw/ -O parse_yaml.sh
    fi
. "${PWD}/parse_yaml.sh"

tools=(vagrant VBoxManage npm sass compass gulp ruby gem bower)

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
        wget -q -N https://getcomposer.org/composer.phar -O composer.phar
    else
        php composer.phar self-update
    fi

#download c3.php for running Codeception remote coverage
 if [ ! -f "c3.php" ]
    then
        echo "Downloading c3.php"
        wget -q -N https://raw.github.com/Codeception/c3/2.0/c3.php -O c3.php
    fi

# download codecept.phar for running tests
 if [ ! -f "codecept.phar" ]
    then
        echo "Downloading codecept.phar"
        wget -q -N http://codeception.com/codecept.phar -O codecept.phar
    fi

# make .env if not already created
if [ ! -f ".env" ]
    then
    cp .env.example .env
    echo ".env was created from example file"
    fi

# load env vars
. "${PWD}/.env"


# make puphpet/config.yaml if not already created
if [ ! -f "puphpet/config.yaml" ]
    then
    cp puphpet/config.yaml.example puphpet/config.yaml
    echo "puphpet/config.yaml was created from example file"
    fi

# make puphpet/config-custom.yaml if not already created
if [ ! -f "puphpet/config-custom.yaml" ]
    then
    cp puphpet/config-custom.yaml.example puphpet/config-custom.yaml
    echo "puphpet/config-custom.yaml was created from example file"
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

# eval the machines.yaml config (parse_yaml can't go deep enough to reach machines)
eval $(parse_yaml puphpet/config-custom.yaml "config__")

# get host name
hostname=$(basename ${APP_URL})

# flush any old virtual boxes
if [ ! -f "vm_flush.sh" ]; then
    wget https://raw.githubusercontent.com/paulbunyannet/bash/master/virtualbox/vm_flush.sh
fi
. "${PWD}/vm_flush.sh" -h "${hostname}" -m "${config__vm__hostname}"

# do vagrant stuff
vagrant destroy -f
vagrant up
vagrant ssh -c "cd /var/www; php composer.phar install;"
# generate new Laravel app key
vagrant ssh -c "cd /var/www; php artisan key:generate;"

# generate new wordpress auth keys
vagrant ssh -c "cd /var/www; php artisan wp:keys --file=.env;"

# run artisan migration
vagrant ssh -c "cd /var/www; php artisan migrate"

#fin