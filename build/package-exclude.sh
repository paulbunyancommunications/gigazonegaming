#!/usr/bin/env bash

baseDir=$1
if [[ $baseDir == build* ]];
then
    while read p; do
        dir=${PWD}/${baseDir}${p}
        #echo ${dir}
        if [ -d "${dir}" ];
        then
          rm -rf ${dir}
        elif [ -f "${dir}" ];
          then
          rm -f ${dir}
        fi
    done <${PWD}/build/.package-exclude
fi