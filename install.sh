#!/usr/bin/env bash

# -------------------------------------------------------------
# Make sure that WORKSPACE is set
# http://stackoverflow.com/a/13864829/405758

if [ -z ${WORKSPACE+x} ];
    then
        WORKSPACE=${PWD};
fi;

# -------------------------------------------------------------
# Make sure that decrypt_password is set, if not get it from the .enc-pass file
# http://stackoverflow.com/a/13864829/405758

if [ -z ${decrypt_password+x} ];
    then
        if [ ! -f ./.enc-pass ];
            then
                echo ".enc-pass file is missing, could not set decrypt_password string. Exiting install"
                exit 1
            fi;
        decrypt_password=$(cat .enc-pass);
fi;

# -------------------------------------------------------------
# decrypt all files needed for build to work

bash ${WORKSPACE}/decrypt-files.sh -w "${WORKSPACE}" -p "${decrypt_password}"

# -------------------------------------------------------------
# Get latest main installer for npm, composer and vagrant

latestBashPackageCommitHash=$(git ls-remote https://github.com/paulbunyannet/bash.git | grep HEAD | awk '{ print $1}')
wget -N -q https://raw.githubusercontent.com/paulbunyannet/bash/${latestBashPackageCommitHash}/setup/puphpet/install_and_setup_assets_in_vagrant_box.sh -O install_runner.sh
. install_runner.sh

# -------------------------------------------------------------
# download codecept.phar for running tests

 if [ ! -f "codecept.phar" ]
    then
        echo "Downloading codecept.phar"
        wget -q -N http://codeception.com/codecept.phar -O codecept.phar
    fi

# -------------------------------------------------------------
# cleanup Wordpress install

if [ -d "public_html/wp/wp-content" ]
then
    echo "Removing the wp/wp-content folder"
    rm -rf public_html/wp/wp-content
fi

if [ -f "public_html/wp/wp-config-sample.php" ]
then
    echo "Removing the wp/sp-config-sample.php file"
    rm -f public_html/wp/wp-config-sample.php
fi

if [ -f "public_html/wp/.htaccess" ]
then
    echo "Removing the wp/.htaccess file"
    rm -f public_html/wp/.htaccess
fi

# -------------------------------------------------------------
# Make cache directory and make sure it's writable

vagrant ssh -c "cd /var/www; mkdir -m 0770 cache || echo ''"

# -------------------------------------------------------------
# update composer libraries

vagrant ssh -c "cd /var/www; php composer.phar update;"

# -------------------------------------------------------------
# generate new Laravel app key

vagrant ssh -c "cd /var/www; php artisan key:generate;"

# -------------------------------------------------------------
# generate new wordpress auth keys

vagrant ssh -c "cd /var/www; php artisan wp:keys --file=.env;"

# -------------------------------------------------------------
# run artisan migration

vagrant ssh -c "cd /var/www; php artisan migrate"