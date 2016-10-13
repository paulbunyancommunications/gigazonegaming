#!/usr/bin/env bash



function program_is_installed {
  # set to 1 initially
  local return_=1
  # set to 0 if not found
  type $1 >/dev/null 2>&1 || { local return_=0; }
  # return value
  echo "$return_"
}
if type $1 >/dev/null 2>&1; then
#    cd /usr/bin
    sudo yum install -y centos-release-SCL epel-release
    sudo wget http://nodejs.org/dist/v6.8.0/node-v6.8.0.tar.gz
    sudo tar xzvf node-v* && cd node-v*
    sudo yum install gcc gcc-c++
    ./configure
    make
    sudo make install
else
    echo "node is installed"
fi