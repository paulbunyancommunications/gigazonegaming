#!/usr/bin/env bash
if [ -z "$1" ]; then
    echo "A commit message is required!"; exit 1;
fi;

message="$1"
#http://stackoverflow.com/a/12142066/405758
branch="$(git rev-parse --abbrev-ref HEAD)"

if [ "$branch" -eq "master" ]; then
    echo "You should not be doing commits directly to the ${branch} branch!"; exit 1;
fi;

git commit -m "${message}"

if [ "$branch" -eq "develop" ]; then
    git checkout master
    git merge develop
fi
git push
git checkout develop