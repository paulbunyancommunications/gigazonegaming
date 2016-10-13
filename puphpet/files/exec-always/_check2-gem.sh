#!/usr/bin/env bash

gems=(sass compass gulp)

function program_is_installed {
  # set to 1 initially
  local return_=1
  # set to 0 if not found
  type $1 >/dev/null 2>&1 || { local return_=0; }
  # return value
  echo "$return_"
}
if program_is_installed gem; then
    echo "ruby is installed"
else
    npm install ruby --save
fi

# for each tool, make sure it's available to the current user
for i in "${gems[@]}"; do
    if ! gem spec ${i} > /dev/null 2>&1; then
      echo "Gem "${i}" is not installed!"
      gem install ${i}
    else
      echo "Gem "${i}" is installed! checking for updates :)"
      gem update sass
    fi
done