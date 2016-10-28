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
    cmp --silent ${ws}/${prefix[i]}.enc.temp ${ws}/${prefix[i]}.enc || echo "${ws}/${prefix[i]}.enc does not match new encrypted file."; mv ${ws}/${prefix[i]}.enc.temp ${ws}/${prefix[i]}.enc

done