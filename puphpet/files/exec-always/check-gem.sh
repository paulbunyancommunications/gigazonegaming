#!/usr/bin/env bash


function check_if_is_installed {
    if ! gem spec $1 > /dev/null 2>&1; then
      echo "Gem $1 is not installed!"
    fi
}