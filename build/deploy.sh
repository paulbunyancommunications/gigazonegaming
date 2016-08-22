#!/usr/bin/env bash
# Because of an issue with the build file that has yet to be resolved,
# the build:post_deploy task needs to be run separate from the main build:deploy task
# pass in the environment variable for as the first parameter
# pass in metrics flag as the second parameter
# eg: "bash deploy.sh development 1" will run deployment in development mode with metrics
env=$1
metrics=$2
dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

#http://stackoverflow.com/a/8217870
safeRunCommand() {
  typeset cmnd="$*"
  typeset ret_code

  echo cmnd=$cmnd
  eval $cmnd
  ret_code=$?
  if [ $ret_code != 0 ]; then
    printf "Error : [%d] when executing command: '$cmnd'" $ret_code
    exit $ret_code
  fi
}


deployment="phing build:deploy -Denv ${env} -Ddo_metrics ${metrics}"
cleanup="phing build:post_deploy -Denv ${env}"
push="cd ..; bash push.sh; cd ${dir}"

safeRunCommand "$deployment"
safeRunCommand "$cleanup"
safeRunCommand "$push"