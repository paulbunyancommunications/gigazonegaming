#!/usr/bin/env bash
cd ~/
seleniumScript="wp-cli.sh"
seleniumScriptPath="${PWD}/${seleniumScript}"
seleniumScriptDownload="https://raw.githubusercontent.com/paulbunyannet/bash/master/wp/${seleniumScript}"
if [ -f "${seleniumScriptPath}" ]; then
    sudo rm -f ${seleniumScriptPath}
fi;
sudo wget -O ${seleniumScriptPath} ${seleniumScriptDownload}
. ${seleniumScriptPath}