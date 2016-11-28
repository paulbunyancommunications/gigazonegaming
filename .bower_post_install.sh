#!/usr/bin/env bash
themeResourceRoot="resources/wp-content/themes/gigazone-gaming"
bowerResourceRoot="public_html/bower_components"

# after install put files in the correct place
if [ ! -d "${themeResourceRoot}/sass/libraries" ]; then mkdir ${themeResourceRoot}/sass/libraries; fi;
if [ ! -d "${themeResourceRoot}/sass/libraries/gutenberg" ]; then mkdir ${themeResourceRoot}/sass/libraries/gutenberg; fi;
echo "Copying the Gutenberg library from the ${bowerResourceRoot}/Gutenberg directory to ${themeResourceRoot}/libraries/gutenberg"
cp -a ${bowerResourceRoot}/Gutenberg/. ${themeResourceRoot}/sass/libraries/gutenberg