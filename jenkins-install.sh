#!/usr/bin/env bash

# -------------------------------------------------------------
#
# decrypt all files needed for build to work
#

bash ${WORKSPACE}/decrypt-files.sh -w "${WORKSPACE}" -p "${decrypt_password}"

# -------------------------------------------------------------
#
# The phing files need to be updated to work with jobs
#

for jenkins_phing_config in "${WORKSPACE}/build/config/jenkins.config"  "${WORKSPACE}/build/config/hosts/jenkins.host"
do
    set -- ${jenkins_phing_config}
    if grep -q production ${jenkins_phing_config}; then
            sed 's/production/jenkins/' ${jenkins_phing_config} | tee ${jenkins_phing_config} >/dev/null
        fi
done

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
[ -e /var/www/Vagrantfile ] && rm /var/www/Vagrantfile
mv /var/www/Vagrantfile_jenkins /var/www/Vagrantfile
[ -e /var/www/codeception.yml ] && rm /var/www/codeception.yml
mv /var/www/codeception_jenkins.yml /var/www/codeception.yml
[ -e /var/www/codeception.yml ] && rm /var/www/codeception.yml
mv /var/www/codeception_jenkins.yml /var/www/codeception.yml
[ -e /var/www/puphpet/config-custom.yml ] && rm /var/www/puphpet/config-custom.yml
mv /var/www/puphpet/config_jenkins.yml /var/www/puphpet/config-custom.yml
bash install.sh

# -------------------------------------------------------------
#
# Create cache directory in VM and set it to writable
#

vagrant ssh -c "cd /var/www; mkdir -m 0770 cache || echo ''"

# -------------------------------------------------------------
#
# Make sure that npm install was run and then run gulp
#
echo "now installing npm and running bulp for the first time"
vagrant ssh -c "cd /var/www; npm install";
vagrant ssh -c "cd /var/www; gulp";

# -------------------------------------------------------------
#
# Make sure that bower packages are installed
#
echo "Now running bower install"
vagrant ssh -c "cd /var/www; bower install";

# -------------------------------------------------------------
#
# Make sure that composer install
#
echo "Now installing composer"
vagrant ssh -c "cd /var/www; composer install"

exit 0