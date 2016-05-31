#!/usr/bin/env bash

# if not already downloaded, get the parse_yaml.sh script for parsing yaml config files
if [ ! -f "parse_yaml.sh" ]
    then
        wget https://gist.githubusercontent.com/pkuczynski/8665367/raw/ -O parse_yaml.sh
    fi
. "${PWD}/parse_yaml.sh"

tools=(/usr/bin/vagrant /usr/bin/VBoxManage /usr/bin/npm /usr/local/bin/sass /usr/local/bin/compass /usr/bin/gulp /usr/bin/ruby /usr/bin/gem /usr/bin/bower)

# for each tool, make sure it's available to the current user
for i in "${tools[@]}"; do
	command -v ${i} >/dev/null 2>&1 || { echo "${i} not installed, aborting!" >&2; exit 1;}
done

# install npm libraries
echo "Getting Node dependencies!"
/usr/bin/cp -f ${WORKSPACE}_assets/nm_cache.tar ${WORKSPACE}
/usr/bin/tar xf nm_cache.tar
/usr/bin/npm update
/usr/bin/rm -f ${WORKSPACE}/nm_cache.tar

# install bower dependencies
echo "Getting Bower dependencies!"
/usr/bin/cp -f ${WORKSPACE}_assets/bower_cache.tar ${WORKSPACE}/public_html
/usr/bin/tar xf ${WORKSPACE}/public_html/bower_cache.tar
/usr/bin/bower update
/usr/bin/rm -f ${WORKSPACE}/public_html/bower_cache.tar

# install bower dependencies
echo "Running gulp for the first time!"
/usr/bin/gulp

# copy the development deploy config to jenkins one
cp ${PWD}/build/config/development.config ${PWD}/build/config/jenkins.config
cp ${PWD}/build/config/hosts/development.host ${PWD}/build/config/hosts/jenkins.host

#download composer.phar for running composer commands
 if [ ! -f "${PWD}/composer.phar" ]
    then
        echo "Downloading composer.phar"
        wget -q -N https://getcomposer.org/composer.phar -O composer.phar -P ${PWD}
    else
        php composer.phar self-update
    fi

#download c3.php for running Codeception remote coverage
 if [ ! -f "${PWD}/c3.php" ]
    then
        echo "Downloading c3.php"
        wget -q -N https://raw.github.com/Codeception/c3/2.0/c3.php -O c3.php -P ${PWD}
    fi

# download codecept.phar for running tests
 if [ ! -f "${PWD}/codecept.phar" ]
    then
        echo "Downloading codecept.phar"
        wget -q -N http://codeception.com/codecept.phar -O codecept.phar -P ${PWD}
    fi

# make .env if not already created
if [ ! -f ".env" ]
    then
    cp ${PWD}/.env.example ${PWD}/.env
    echo "${PWD}/.env was created from example file"
    fi

# load env vars
. "${PWD}/.env"


# make puphpet/config.yaml if not already created
if [ ! -f "${PWD}/puphpet/config.yaml" ]
    then
    cp ${PWD}/puphpet/config.yaml.example ${PWD}/puphpet/config.yaml
    echo "${PWD}/puphpet/config.yaml was created from example file"
    fi

# make puphpet/config-custom.yaml if not already created
if [ ! -f "${PWD}/puphpet/config-custom.yaml" ]
    then
    cp ${PWD}/puphpet/config-custom.yaml.example ${PWD}/puphpet/config-custom.yaml
    echo "${PWD}/puphpet/config-custom.yaml was created from example file"
    fi

# cleanup Wordpress install
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

# flush out old virtual machines
# eval the config-custom.yaml config to get the machine name
eval $(parse_yaml ${PWD}/puphpet/config-custom.yaml "config__")
# get host name
hostname=$(basename ${APP_URL})
if [ ! -f "vm_flush.sh" ]; then
    wget https://raw.githubusercontent.com/paulbunyannet/bash/master/virtualbox/vm_flush.sh
fi
. "${PWD}/vm_flush.sh" -h "${hostname}" -m "${config__vm__hostname}"

# do vagrant stuff
/usr/bin/vagrant destroy -f
/usr/bin/vagrant up
/usr/bin/vagrant ssh -c "cd /var/www; php composer.phar install;"
# generate new Laravel app key
/usr/bin/vagrant ssh -c "cd /var/www; php artisan key:generate;"

# generate new wordpress auth keys
/usr/bin/vagrant ssh -c "cd /var/www; php artisan wp:keys --file=.env;"

# run artisan migration
/usr/bin/vagrant ssh -c "cd /var/www; php artisan migrate"

#fin