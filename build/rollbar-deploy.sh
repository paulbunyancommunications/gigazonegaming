#!/usr/bin/env bash
build_dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
deploy=$1
source ${build_dir}/config/${deploy}.config
echo ${deploy_dir}
echo ${deploy_url}
echo ${deploy_rollbar}
ACCESS_TOKEN=${deploy_rollbar}
ENVIRONMENT=${deploy}
LOCAL_USERNAME=`whoami`
REVISION=`git log -n 1 --pretty=format:"%H"`

curl https://api.rollbar.com/api/1/deploy/ \
  -F access_token=${ACCESS_TOKEN} \
  -F environment=${ENVIRONMENT} \
  -F revision=${REVISION} \
  -F local_username=${LOCAL_USERNAME}