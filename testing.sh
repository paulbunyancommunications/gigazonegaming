#!/usr/bin/env bash
NC="\033[0m" # No Color
TAB=" - "
COLS=$(tput cols)
#log file for output of tests
log="codecept-result.log"
FINISHED=false
IN_CONTAINER=false

#http://wiki.bash-hackers.org/snipplets/print_horizontal_line#a_line_across_the_entire_width_of_the_terminal
TAB_FILL=$(printf '%*s\n' "${COLUMNS:-$(echo ${COLS})}" '' | tr ' ' -);

# Start the loging
touch ${log}
echo -n "" > ${log}
tail -f ${log} &

# check if inside a docker container or not
if [ -f /.dockerenv ]; then
    IN_CONTAINER=true
else
    IN_CONTAINER=false
fi
if [ "$IN_CONTAINER" = false ]; then
        echo ${TAB_FILL}
        printf "\n${TAB}Tests should be run inside the Docker code container${NC}"
        exit 1
fi;


# -------------------------------------------------------------------------------------
# Run config migrations if artisan command exists
if [ -f "artisan" ]
then
    php artisan config:clear && php artisan migrate >/dev/null 2>&1
fi
# -------------------------------------------------------------------------------------
# go though the ./tests folder and get all the yaml files.
# Then split the file name to get the suite name
rm -f ./tests/test-suites | true

# find all the test suite files
find ./tests -name '*.yml' -print | grep suite > ./tests/test-suites

# loop though the files in the ./tests/test-suites file
cat ./tests/test-suites | while read s; do

  suite=$(basename ${s})

  # explode file name by dot
  IFS='.' read -a suitePart <<< "${suite}"

  # append to final value
  suiteFinal="${suiteFinal} ${suitePart[0]}"

  # write out to the test-suites file
  echo ${suiteFinal} > ./tests/test-suites

done
# test suites
# read and explode the list by delimiter
#now the testSuites variable has a list of all the suites by name
IFS=' ' read -r -a testSuites <<< $(cat ./tests/test-suites)
# do codeception cleanup
codecept clean && codecept build

errorKeys=("PHPUnit_Framework_Exception" "FATAL ERROR. TESTS NOT FINISHED" "FAILURES!" "ERRORS!" "TESTS EXECUTION TERMINATED")
errorMessage="TESTS FAILED! See ${log} for output."
failedReAttempt="TESTS FAILED. Will retry failed tests."

# loop though all the text suites, marking the time they started, ended and how long they took
for ((i=0; i<${#testSuites[@]}; i++))
do

    SECONDS=0;
    c=0
    # http://stackoverflow.com/questions/24367088/print-a-character-till-end-of-line
    printf "\n${TAB_FILL}\n"
    printf "${TAB}Now running ${testSuites[i]} test suite, started at $(date +'%Y-%m-%d %H:%M:%S') ${TAB}${NC}\n"


    # run tests
    codecept run ${testSuites[i]} -v -f > ${log} 2>&1

    took=${SECONDS}
    ((sec=took%60, took/=60, min=took%60, hrs=took/60))
    timestamp=$(printf "%d hours, %02d minutes and %02d seconds" $hrs $min $sec)

    # check for failed tests and rerun them (Firefox might have lost
    # connection for instance in an acceptance test)
    if [ -f "tests/_output/failed" ]
        then
            printf "\n${TAB_FILL}\n"
            printf "${TAB}${failedReAttempt} Started at $(date +'%Y-%m-%d %H:%M:%S')${TAB}${NC}\n"
            # run tests in the suite
            codecept run -g failed -v -f > ${log} 2>&1
    fi
    # check for errors
    # http://stackoverflow.com/a/2295565
    while read -r line
    do
        for e in ${errorKeys[@]}
        do
            case "$line" in *"$e"*)
                printf "\n${TAB}${errorMessage}${TAB}${NC}\n"
                printf "${TAB}Test suite ${testSuites[i]} ran, took ${timestamp} to complete${TAB}${NC}\n"
                # kill the tail
                kill %tail >/dev/null 2>&1
                exit 1;
            esac
        done
    done <${log}

    printf "\n${TAB}Completed ${testSuites[i]} test suite, ended at $(date +'%Y-%m-%d %H:%M:%S')${TAB}${NC}\n"
    printf "${TAB}Test suite ${testSuites[i]} complete, took ${timestamp} to complete${TAB}${NC}\n"
done



# kill the tail
kill %tail >/dev/null 2>&1
