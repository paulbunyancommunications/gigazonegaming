#!/usr/bin/env bash

#------------------------------------------
# Variables to check for tools
#------------------------------------------

vagrant=$(which vagrant)
npm=$(which npm)
sass=$(which sass)
compass=$(which compass)
gulp=$(which gulp)
ruby=$(which ruby)
gem=$(which gem)
bower=$(which bower)


tools=(${vagrant} ${npm} ${sass} ${compass} ${gulp} ${ruby} ${gem} ${bower})

# for each tool, make sure it's available to the current user
for i in "${tools[@]}"; do
	command -v ${i} >/dev/null 2>&1 || { echo "${i} not installed, aborting!" >&2; exit 1;}
done

#------------------------------------------
# Install npm libraries
#------------------------------------------

echo "Getting Node dependencies!"
cp -f ${WORKSPACE}_assets/nm_cache.tar ${WORKSPACE}
tar xf nm_cache.tar
eval "${npm} update"
rm -f ${WORKSPACE}/nm_cache.tar

#------------------------------------------
# Install bower dependencies
#------------------------------------------

echo "Getting Bower dependencies!"
cp -f ${WORKSPACE}_assets/bower_cache.tar ${WORKSPACE}/public_html
tar xf ${WORKSPACE}/public_html/bower_cache.tar
eval "${bower} update"
rm -f ${WORKSPACE}/public_html/bower_cache.tar

#------------------------------------------
# Run gulp
#------------------------------------------

echo "Running gulp for the first time!"
eval "${npm} install --save-dev gulp"
eval "${gulp}"

#------------------------------------------
# Get create the config
# file needed for deployment
#------------------------------------------

cp ${PWD}/build/config/development.config ${PWD}/build/config/jenkins.config
cp ${PWD}/build/config/hosts/development.host ${PWD}/build/config/hosts/jenkins.host

#------------------------------------------
# Download composer.phar
# for running composer commands
#------------------------------------------

 if [ ! -f "${PWD}/composer.phar" ]
    then
        echo "Downloading composer.phar"
        wget -q -N https://getcomposer.org/composer.phar -O composer.phar -P ${PWD}
    else
        php composer.phar self-update
    fi

#------------------------------------------
# Download c3.php for running
# Codeception remote coverage
#------------------------------------------


 if [ ! -f "${PWD}/c3.php" ]
    then
        echo "Downloading c3.php"
        wget -q -N https://raw.github.com/Codeception/c3/2.0/c3.php -O c3.php -P ${PWD}
    fi

#------------------------------------------
# Download codecept.phar for running tests
#------------------------------------------

 if [ ! -f "${PWD}/codecept.phar" ]
    then
        echo "Downloading codecept.phar"
        wget -q -N http://codeception.com/codecept.phar -O codecept.phar -P ${PWD}
    fi

#------------------------------------------
# make .env if not already created
#------------------------------------------

if [ ! -f ".env" ]
    then
    cp ${PWD}/.env.example ${PWD}/.env
    echo "${PWD}/.env was created from example file"
    fi

# load env vars
. "${PWD}/.env"


#------------------------------------------
# Make puphpet/config.yaml if
# not already created
#------------------------------------------


if [ ! -f "${PWD}/puphpet/config.yaml" ]
    then
    cp ${PWD}/puphpet/config.yaml.example ${PWD}/puphpet/config.yaml
    echo "${PWD}/puphpet/config.yaml was created from example file"
    fi

#------------------------------------------
# Make puphpet/config-custom.yaml
# if not already created
#------------------------------------------


if [ ! -f "${PWD}/puphpet/config-custom.yaml" ]
    then
    cp ${PWD}/puphpet/config-custom.yaml.example ${PWD}/puphpet/config-custom.yaml
    echo "${PWD}/puphpet/config-custom.yaml was created from example file"
    fi


#------------------------------------------
# Spin up box and do setup
# in box prior to tests
#------------------------------------------

eval "${vagrant} destroy -f && ${vagrant} up"
eval "${vagrant} ssh -c \"cd /var/www; php composer.phar install;\""

#------------------------------------------
# Generate new Laravel app key
#------------------------------------------

eval "${vagrant} ssh -c \"cd /var/www; php artisan key:generate;\""

#------------------------------------------
# generate new wordpress auth keys
#------------------------------------------

eval "${vagrant} ssh -c \"cd /var/www; php artisan wp:keys --file=.env;\""

#------------------------------------------
# run artisan migration
#------------------------------------------

eval "${vagrant} ssh -c \"cd /var/www; php artisan migrate\""

#------------------------------------------
# cleanup Wordpress install
#------------------------------------------

if [ -d "${PWD}/public_html/wp/wp-content" ]
then
    rm -rf ${PWD}/public_html/wp/wp-content
fi

if [ -f "${PWD}/public_html/wp/wp-config-sample.php" ]
then
    rm -f ${PWD}/public_html/wp/wp-config-sample.php
fi

if [ -f "${PWD}/public_html/wp/.htaccess" ]
then
    rm -f ${PWD}/public_html/wp/.htaccess
fi

#fin