#!/usr/bin/env bash
log="codecept-result.log"
finished=false
touch ${log}
echo -n "" >${log}
# run tests inside vagrant box
failed=$1
# tail the log file to see tests while they run
tail -f ${log} &
if [[ -n "$failed" ]]; then
    vagrant ssh -c "cd /var/www; php artisan config:clear; php artisan migrate; php codecept.phar run -g ${failed} --debug" > ${log} 2>&1
else
    vagrant ssh -c "cd /var/www; php artisan config:clear; php artisan migrate; php codecept.phar clean; php codecept.phar build; php codecept.phar run --coverage-html --coverage-xml;" > ${log} 2>&1
fi
# kill the tail
kill %tail >/dev/null 2>&1
# reset the migrations in the box
vagrant ssh -c "cd /var/www; php artisan migrate;"
# check for errors
if grep "FAILURES!" ${log}
    then
       echo "TESTS FAILED. See ${log} for output."; exit 1;
    fi
if grep "PHPUnit_Framework_Exception" ${log}
    then
        echo "PHPUnit_Framework_Exception thrown, see ${log} for output."; exit 1;
    fi