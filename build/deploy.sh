#!/usr/bin/env bash
# Because of an issue with the build file that has yet to be resolved,
# the build:post_deploy task needs to be run separate from the main build:deploy task
# pass in the environment variable for as the first parameter
# pass in metrics flag as the second parameter
# eg: "bash deploy.sh development 1" will run deployment in development mode with metrics
env=$1
metrics=$2
phing build:deploy -Denv ${env} -Ddo_metrics ${metrics}
phing build:post_deploy -Denv ${env}