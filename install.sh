#!/usr/bin/env bash

# if not already downloaded, get the parse_yaml.sh script for parsing yaml config files
if [ ! -f "parse_yaml.sh" ]
    then
        wget https://gist.githubusercontent.com/pkuczynski/8665367/raw/ -O parse_yaml.sh
    fi
. parse_yaml.sh

tools=(vagrant VBoxManage npm sass compass gulp ruby gem compass bower)

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
        yes | php codecept.phar self-update
    fi

# make .env if not already created
if [ ! -f ".env" ]
    then
    cp .env.example .env
    echo ".env was created from example file"
    fi

# load env vars
. ".env"


# make puphpet/config.yaml if not already created
if [ ! -f "puphpet/config.yaml" ]
    then
    cp puphpet/config.yaml.example puphpet/config.yaml
    echo "puphpet/config.yaml was created from example file"
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
eval $(parse_yaml puphpet/machines.yaml "config__")

# get host name
hostname=$(basename ${APP_URL})
# start the boxes array, we'll look for each of these and destroy the box if found
boxes=()
boxes+=("${hostname}")
boxes+=("${config__machines__machine_one__hostname}")
boxes+=("${PWD##*/}_${config__machines__machine_one__id}")

# go though all the boxes and find the matching box
# to unset, should be named [directory]_[box_id]_[hash]
while read -r line; do
    if [[ $line == *"${PWD##*/}_${config__machines__machine_one__id}"* ]]
        then
           echo $line > tmp_vm.txt
           # http://unix.stackexchange.com/questions/137030/how-do-i-extract-the-content-of-quoted-strings-from-the-output-of-a-command
           boxes+=($(grep -o '".*"' tmp_vm.txt | sed 's/"//g'))
           rm -f tmp_vm.txt
        fi
done <<< "$(VBoxManage list vms)"

# for each of the boxes found, unset it with VBoxManage
for i in "${boxes[@]}"; do
    echo ${i}
	VBoxManage unregistervm "${i}" --delete >/dev/null 2>&1
done

# do vagrant stuff
vagrant destroy -f
vagrant up
vagrant ssh -c "cd /var/www; php composer.phar install;"
# generate new Laravel app key
vagrant ssh -c "cd /var/www; php artisan key:generate;"

# generate new wordpress auth keys
vagrant ssh -c "cd /var/www; php artisan wp:keys --file=.env;"

#fin