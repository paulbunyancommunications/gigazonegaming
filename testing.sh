#!/usr/bin/env bash
NC='\033[0m' # No Color
TAB=' - '
COLS=$(tput cols)
#log file for output of tests
log="codecept-result.log"
finished=false
touch ${log}
echo -n "" >${log}
tail -f ${log} &

# run migrations if artisan command exists
if [ -f "artisan" ]
then
    vagrant ssh -c "cd /var/www; php artisan config:clear && php artisan migrate;" >/dev/null 2>&1
fi
# test suites
testSuites=(
"unit"
"integration"
"functional"
"acceptance"
)

# do codeception cleanup
wget -N -q http://codeception.com/codecept.phar >/dev/null 2>&1
vagrant ssh -c "cd /var/www; php codecept.phar clean && php codecept.phar build" >/dev/null 2>&1

errorKeys=("PHPUnit_Framework_Exception" "FATAL ERROR. TESTS NOT FINISHED" "FAILURES!" "ERRORS!" "TESTS EXECUTION TERMINATED")
errorMessage="TESTS FAILED. See ${log} for output.";

# loop though all the text suites, marking the time they started, ended and how long they took
for ((i=0; i<${#testSuites[@]}; i++))
do
    SECONDS=0;
    c=0
    # http://stackoverflow.com/questions/24367088/print-a-character-till-end-of-line
    echo -e "\033[49m$(for ((i=0; i<($COLS - 2); i++));do printf ${TAB}; done; echo)${NC}"

    echo -e "\033[30;48;5;200m${TAB}Now running ${testSuites[i]} test suite, started at $(date +'%Y-%m-%d %H:%M:%S')${TAB}${NC}"

    # run tests in the suite and fail fast
    vagrant ssh -c "cd /var/www; php codecept.phar run ${testSuites[i]} -v -f;" > ${log} 2>&1

    took=${SECONDS}
    ((sec=took%60, took/=60, min=took%60, hrs=took/60))
    timestamp=$(printf "%d hours, %02d minutes and %02d seconds" $hrs $min $sec)

    # check for errors
    # http://stackoverflow.com/a/2295565
    while read -r line
    do
        for e in ${errorKeys[@]}
        do
            case "$line" in *"$e"*)
                echo -e "\033[48;5;9m${TAB}${errorMessage}${TAB}${NC}"
                echo -e "\033[48;5;6m${TAB}Test suite ${testSuites[i]} ran, took ${timestamp} to complete${TAB}${NC} "
                printf "\n\n"
                # kill the tail
                kill %tail >/dev/null 2>&1
                exit 1;
            esac
        done
    done <${log}

    echo -e "\033[30;48;5;82m${TAB}Completed ${testSuites[i]} test suite, ended at $(date +'%Y-%m-%d %H:%M:%S')${TAB}${NC}"
    echo -e "\033[30;48;5;6m${TAB}Test suite ${testSuites[i]} complete, took ${timestamp} to complete${TAB}${NC}"
    printf "\n"
done



# kill the tail
kill %tail >/dev/null 2>&1
