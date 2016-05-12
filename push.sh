#!/usr/bin/env bash

#http://stackoverflow.com/a/12142066/405758
branch="$(git rev-parse --abbrev-ref HEAD)"
if [ "$branch" == "master" ]; then
    echo "You should not be doing commits directly to the ${branch} branch!"; exit 1;
fi;

if [ -n "$1" ]; then
    message="$1"
    git commit -m "${message}"
fi;
if [ "$branch" == "develop" ]; then
    git checkout master
    git merge develop
fi
git push
git checkout develop