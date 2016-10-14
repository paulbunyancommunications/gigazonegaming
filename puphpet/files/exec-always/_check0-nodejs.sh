##!/usr/bin/env bash
#
#
#
#function program_is_installed {
#  # set to 1 initially
#  local return_=1
#  # set to 0 if not found
#  type $1 >/dev/null 2>&1 || { local return_=0; }
#  # return value
#  echo "$return_"
#}
if type $1 >/dev/null 2>&1; then
#    echo "starting yum remove -y nodejs npm"
#    sudo yum remove -y nodejs npm
#    echo "done yum remove -y nodejs npm"
#    cd /var/www
#    echo "starting ldconfig"
#    sudo ldconfig
#    echo "done ldconfig"
    echo "starting yum install -y gcc-c++ make"
    sudo yum install -y gcc-c++ make
    echo "done yum install -y gcc-c++ make"
#    echo "starting ldconfig"
#    sudo ldconfig
#    echo "done ldconfig"
#    echo "starting curl -sL https://rpm.nodesource.com/setup_6.x | sudo -E bash -"
#    sudo curl -sL https://rpm.nodesource.com/setup_6.x | sudo -E bash -
#    echo "done starting curl -sL https://rpm.nodesource.com/setup_6.x | sudo -E bash -"
#    echo "starting yum install nodejs"
#    sudo yum install nodejs
#    echo "done yum install nodejs"
#    node -v
#    node --version
else
    echo "node is installed"
fi