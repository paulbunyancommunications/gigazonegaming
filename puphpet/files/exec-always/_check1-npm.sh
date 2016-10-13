#!/usr/bin/env bash



function program_is_installed {
  # set to 1 initially
  local return_=1
  # set to 0 if not found
  type $1 >/dev/null 2>&1 || { local return_=0; }
  # return value
  echo "$return_"
}
if program_is_installed npm; then
    echo "npm is installed"
    echo "trying to update node"
    sudo npm cache clean -f
    sudo npm install -g n
    sudo n stable
    node -v

else
    sudo yum install npm
    node -v
fi
