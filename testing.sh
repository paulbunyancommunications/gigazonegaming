#!/usr/bin/env bash
log="codecept-result.log"
finished=false
touch ${log}
echo -n "" >${log}
# run tests inside vagrant box
failed=$1
if [[ -n "$failed" ]]; then
    vagrant ssh -c "cd /var/www; php artisan config:clear; php artisan migrate; php codecept.phar run -g ${failed} --debug" > ${log} 2>&1
else
    vagrant ssh -c "cd /var/www; php artisan config:clear; php artisan migrate; php codecept.phar clean; php codecept.phar build; php codecept.phar run --coverage-html --coverage-xml;" > ${log} 2>&1
fi
cat ${log}
# reset the migrations in the box
vagrant ssh -c "cd /var/www; php artisan migrate;"
if grep "FAILURES!" ${log}
    then
       echo "TESTS FAILED!"; exit 1;
    fi
