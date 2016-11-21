#!/usr/bin/env bash
cd /var/www
source .env
#http://stackoverflow.com/a/11385736/405758
echo "127.0.0.1 $(echo ${APP_URL} | awk -F/ '{print $3}')" >> /etc/hosts