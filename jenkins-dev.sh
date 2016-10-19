#!/usr/bin/env bash

# -------------------------------------------------------------
#
# http://www.shellhacks.com/en/Encrypt-And-Decrypt-Files-With-A-Password-Using-OpenSSL
# decrypt the phing config file
# the encrypted file is a copy of production.config,
# re encrypt production.config to config.enc if
# changes are needed.
#

if [ -f "${WORKSPACE}/build/config/config.enc" ]
    then
        openssl enc -aes-256-cbc -d -in ${WORKSPACE}/build/config/config.enc -out ${WORKSPACE}/build/config/jenkins.config -pass pass:${decrypt_password}
        if grep -q production "${WORKSPACE}/build/config/jenkins.config"; then
            sed 's/production/jenkins/' ${WORKSPACE}/build/config/jenkins.config | tee ${WORKSPACE}/build/config/jenkins.config
        fi
fi


# -------------------------------------------------------------
#
# decrypt the phing host config file
# the encrypted file is a copy of production.host,
# re encrypt production.host to host.enc if
# changes are needed.
#

if [ -f "${WORKSPACE}/build/config/hosts/host.enc"  ]
    then
        openssl enc -aes-256-cbc -d -in ${WORKSPACE}/build/config/hosts/host.enc -out ${WORKSPACE}/build/config/hosts/jenkins.host -pass pass:${decrypt_password}
        if grep -q production "build/config/hosts/jenkins.host"; then
            sed 's/production/jenkins/' ${WORKSPACE}/build/config/hosts/jenkins.host | tee ${WORKSPACE}/build/config/hosts/jenkins.host
        fi
fi


# -------------------------------------------------------------
#
# decrypt the .env file
# the encrypted file is a copy of .env used in development,
# re encrypt .env to .env.enc if
# changes are needed.
#

if [ -f "${WORKSPACE}/.env.enc" ]
    then
        openssl enc -aes-256-cbc -d -in ${WORKSPACE}/.env.enc -out ${WORKSPACE}/.env -pass pass:${decrypt_password}
fi


# -------------------------------------------------------------
#
# make puphpet/config.yaml
#

if [ -f "${WORKSPACE}/puphpet/config.enc" ]
    then
        openssl enc -aes-256-cbc -d -in ${WORKSPACE}/puphpet/config.enc -out ${WORKSPACE}/puphpet/config.yaml -pass pass:${decrypt_password}
    else
		cp ${WORKSPACE}/puphpet/config.yaml.example ${WORKSPACE}/puphpet/config.yaml
fi


# -------------------------------------------------------------
#
# make puphpet/config-custom.yaml
#

if [ -f "${WORKSPACE}/puphpet/config-custom.enc" ]
    then
        openssl enc -aes-256-cbc -d -in ${WORKSPACE}/puphpet/config-custom.enc -out ${WORKSPACE}/puphpet/config-custom.yaml -pass pass:${decrypt_password}
    else
		cp ${WORKSPACE}/puphpet/config-custom.yaml.example ${WORKSPACE}/puphpet/config-custom.yaml
fi


# -------------------------------------------------------------
#
# make database/dump/gigazone_wp.sql
#
if [ -f "${WORKSPACE}/database/dump/gigazone_wp.enc" ]
    then
        openssl enc -aes-256-cbc -d -in ${WORKSPACE}/database/dump/gigazone_wp.enc -out ${WORKSPACE}/database/dump/gigazone_wp.sql -pass pass:${decrypt_password}
    else
		touch ${WORKSPACE}/database/dump/gigazone_wp.sql
fi



# -------------------------------------------------------------
#
# make database/dump/gzgaming_champ_db.sql
#
if [ -f "${WORKSPACE}/database/dump/gzgaming_champ_db.enc" ]
    then
        openssl enc -aes-256-cbc -d -in ${WORKSPACE}/database/dump/gzgaming_champ_db.enc -out ${WORKSPACE}/database/dump/gzgaming_champ_db.sql -pass pass:${decrypt_password}
    else
		touch ${WORKSPACE}/database/dump/gzgaming_champ_db.sql
fi



# -------------------------------------------------------------
#
# destroy the box and any left overs,
# install will spin it up.
#

VBoxManage controlvm gigazonegaming.local poweroff || echo "gigazonegaming.local was not powered off, it might not have existed."
VBoxManage unregistervm gigazonegaming.local --delete || echo "gigazonegaming.local was not deleted, it might not have existed."
rm -rf '/var/lib/jenkins/VirtualBox VMs/gigazonegaming.local' || echo "'/var/lib/jenkins/VirtualBox VMs/gigazonegaming.local' was not deleted, it might not have existed."
vagrant destroy
vagrant box update


# -------------------------------------------------------------
#
# do install
#

bash install.sh


# -------------------------------------------------------------
# get Phing
#

vagrant ssh -c "cd /var/www; php composer.phar require phing/phing:2.* --dev"


# -------------------------------------------------------------
#
# Run Metrics
#

bash testing.sh

# -------------------------------------------------------------
#
# Run Cleanup
#

cd ${WORKSPACE}/build
php ././vendor/bin/phing build:cleanup -Denv=jenkins -Ddo_metrics=0