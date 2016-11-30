#!/usr/bin/env bash

resourceRoot="${PWD}/resources"
publicRoot="${PWD}/public_html"
nodeRoot="${PWD}/node_modules"
bowerRoot="${publicRoot}/bower_components"
sassFolder="sass";
themeFolder="wp-content/themes/gigazone-gaming"

mkdir ${publicRoot}/${themeFolder}/libraries || true
mkdir ${resourceRoot}/${themeFolder}/sass/libraries || true
mkdir ${publicRoot}/app/content/libraries || true
mkdir ${resourceRoot}/assets/sass/libraries || true

from=(
    ${nodeRoot}/bootstrap/dist/
    ${nodeRoot}/bootstrap/dist/
    ${nodeRoot}/bootstrap-sass/assets/
    ${nodeRoot}/bootstrap-sass/assets/
    ${bowerRoot}/bourbon/app/assets/stylesheets/
    ${bowerRoot}/bourbon/app/assets/stylesheets/
    ${bowerRoot}/Gutenberg/src/style/
    ${bowerRoot}/Gutenberg/src/style/
)
to=(
    ${publicRoot}/${themeFolder}/libraries/bootstrap
    ${publicRoot}/app/content/libraries/bootstrap
    ${resourceRoot}/${themeFolder}/${sassFolder}/libraries/bootstrap-sass
    ${resourceRoot}/assets/${sassFolder}/libraries/bootstrap-sass
    ${resourceRoot}/assets/sass/libraries/bourbon
    ${resourceRoot}/${themeFolder}/sass/libraries/bourbon
    ${resourceRoot}/${themeFolder}/assets/sass/libraries/gutenberg
    ${resourceRoot}/${themeFolder}/sass/libraries/gutenberg
)

for ((i=0; i<${#from[@]}; i++))
do
    mkdir ${to[i]} || true && cp -r ${from[i]} ${to[i]}
done
