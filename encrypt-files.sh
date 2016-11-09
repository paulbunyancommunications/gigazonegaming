#!/usr/bin/env bash

while [[ $# > 1 ]]
do
key="$1"
shift

case $key in
    -p|--pass)
    password="$1"
    shift
    ;;
esac
case $key in
    -w|--workspace)
    ws="$1"
    shift
    ;;
esac
done

if [ -z ${ws+x} ]; then ws=${PWD}; fi
echo "Workspace is '$ws'"

source ${ws}/enc-files

for ((i=0; i<${#prefix[@]}; i++))
do
    echo "Encrypting: ${ws}/${prefix[i]}${suffix[i]}, ${desc[i]}"
    openssl enc -aes-256-cbc -salt -in ${ws}/${prefix[i]}${suffix[i]} -out ${ws}/${prefix[i]}.enc.temp -pass pass:${password}
    if [ -f ${ws}/${prefix[i]}.enc ]
    then
        mv ${ws}/${prefix[i]}.enc.temp ${ws}/${prefix[i]}.enc
        continue
    fi

    if [[ $(stat -c%s ${ws}/${prefix[i]}.enc.temp) -ge $(stat -c%s ${ws}/${prefix[i]}${suffix[i]}) ]];
    then
        echo "Old file ${ws}/${prefix[i]}${suffix[i]} does not match the file size of the new file so it will be replaced."
        mv ${ws}/${prefix[i]}.enc.temp ${ws}/${prefix[i]}.enc
    fi
    if [ test -f ${ws}/${prefix[i]}.enc.temp ]
    then
        rm ${ws}/${prefix[i]}.enc.temp
    fi
done