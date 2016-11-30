#!/usr/bin/env bash

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

bash ${WORKSPACE}/install.sh

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


exit 0