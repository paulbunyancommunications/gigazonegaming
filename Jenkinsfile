

import java.text.SimpleDateFormat

class Globals {

    static DATE_FORMAT_HUMAN           = new SimpleDateFormat("EEEE',' MMMM dd',' YYYY 'at' HH:mm:ss z");
    static DATE_FORMAT_LOGS            = new SimpleDateFormat("yyyy-MM-dd");
    static DATE_FORMAT_STAMP           = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
    static DATE_JOB_STARTED            = new Date();
    static String[] CONTAINERS         = [];
    static String STAGE                = "Job Started";
    static String GIT_LOG              = "";
    static String BUILD_FOLDER         = "";
    static String ARCHIVE_NAME         = "job-${Globals.SCM_OWNER}-${Globals.SCM_REPO}-${Globals.SCM_BRANCH}";
    static String WORKSPACE            = "";
    static String TAIL_LENGTH          = 1000;
    static String SCM_URL              = "";
    static String SCM_OWNER            = "";
    static String SCM_REPO             = "";
    static String SCM_BRANCH           = "develop";
    static String COMMIT_AUTHOR_EMAIL  = "example@example.com";
    static String COMMIT_AUTHOR_NAME   = "John Doe";
    static String COMMIT_MESSAGE       = "Commit message";
    /* Array of directories to make writable */
    static String[] WRITABLE_DIRS      = [];

}


/*
 * startMessage
 * Output a formatted start message message
 * @prop String stage
 * @prop String message
 * @prop mixed timestamp
 */
def startMessage(String stage, String message = "", timestamp = Globals.DATE_JOB_STARTED) {
    println "\u2605 Started ${stage} ${message} at ${Globals.DATE_FORMAT_STAMP.format(timestamp)} \u2605"
}

/* Output a formatted success message */
def successMessage(String stage, String message = "", timestamp = Globals.DATE_JOB_STARTED) {
    println "\u2713 ${stage} completed ${message} at ${Globals.DATE_FORMAT_STAMP.format(timestamp)} \u263A!"
}

/* Output a formatted warning message */
def warningMessage(String stage, String message = "", timestamp = Globals.DATE_JOB_STARTED) {
    println "\u2297 ${stage} warning: ${message} at ${Globals.DATE_FORMAT_STAMP.format(timestamp)} \u1F631!"
}

/* output a formatted error message */
def errorMessage(String stage, String message = "", timestamp = Globals.DATE_JOB_STARTED) {
    println "\u2297 ${stage} failed: ${message} \u1F631!"
    failJob(stage, message, timestamp)
}
/* fail the job */
def failJob(String stage, String message = "", timestamp = Globals.DATE_JOB_STARTED) {
    try {
        def RESULT = "FAILED"
        def JOB_FAILED_DATE = new Date();
        // Create the error message body
        def JOB_FAILED_BODY = "Build for ${env.JOB_NAME} ${currentBuild.number} ${RESULT}! \n Console output: ${env.BUILD_URL}/console \n Stage \"${stage}\" failed on ${Globals.SCM_OWNER}/${Globals.SCM_REPO}:${Globals.SCM_BRANCH}.\n Stage \"${stage}\" was run on ${Globals.DATE_FORMAT_HUMAN.format(JOB_FAILED_DATE)}\n\n";
        //for (i = 0; i <$(docker-compose config --services).length; i++) {
        //sh "cd ${Globals.WORKSPACE};";
        //sh "${ echo \"\$(docker-compose config --services).trim()\"}";
        //sh "cd ${Globals.WORKSPACE};  echo \"$(docker-compose config --services).trim() > Globals.CONTAINERS"; ";
        //echo "THIS IS A CONTAINER ${Globals.CONTAINERS[1]}";
        //}
        // Get the log outputfrom the code container
        sh "cd ${Globals.WORKSPACE}; docker-compose logs --timestamps code > ./tests/_output/code.log"

        // Get the log outputfrom the firefox container
        sh "cd ${Globals.WORKSPACE}; docker-compose logs --timestamps firefox > ./tests/_output/firefox.log"

        // Get the log outputfrom the web container
        sh "cd ${Globals.WORKSPACE}; docker-compose logs --timestamps web > ./tests/_output/web.log"

        // Get the log outputfrom the hub container
        sh "cd ${Globals.WORKSPACE}; docker-compose logs --timestamps hub > ./tests/_output/hub.log"

        // Zip the output folder for email
        zip dir: "${Globals.WORKSPACE}/tests/_output", glob: '', zipFile: "${Globals.WORKSPACE}/${Globals.ARCHIVE_NAME}-test-output.zip"



        // email teh recipient the log output folder
        emailext attachmentsPattern: "${Globals.ARCHIVE_NAME}-test-output.zip", body: JOB_FAILED_BODY, subject: "Build for ${env.JOB_NAME} ${currentBuild.number} ${RESULT}!", to: "${Globals.COMMIT_AUTHOR_EMAIL}"

        // notify mattermost of this error
        mattermostSend "![${RESULT}](https://jenkins.paulbunyan.net:8443/buildStatus/icon?job=${env.JOB_NAME} 'Icon') ${RESULT} ${env.JOB_NAME} # ${env.BUILD_NUMBER} (<${env.BUILD_URL}|Open Pipe>)(<${env.BUILD_URL}/console|Open Console>)"

        // Bring container down and destroy
        sh "cd ${Globals.WORKSPACE}; docker-compose down -v";

    } catch (error) {
        // if there was an error return it here as a warning.
        warningMessage(stage, error.getMessage())
    }
    // fail the job
    return error("${stage} failed: ${message} on ${Globals.DATE_FORMAT_STAMP.format(timestamp)}")
}

node {
    Globals.DATE_JOB_STARTED = new Date()
    Globals.WORKSPACE = env.WORKSPACE
    Globals.SCM_OWNER = SCM_OWNER
    Globals.SCM_REPO = SCM_REPO
    Globals.SCM_BRANCH = SCM_BRANCH
    Globals.SCM_URL = SCM_URL
    Globals.ARCHIVE_NAME="${env.JOB_NAME}-${env.BUILD_NUMBER}-${Globals.SCM_BRANCH}"
    Globals.WRITABLE_DIRS = ["database", "groovy", "temp", "storage", "cache", "mailings", "tests/_output", "css", "js", "tests"] as String[]

    stage('Setup'){
        /**
        * Clean up the workspace to start with
        */
        Globals.STAGE='Workspace: Clean up for build'
        startMessage(Globals.STAGE)
        try {
            step([$class: 'WsCleanup']);
        } catch (error) {
            errorMessage(Globals.STAGE, error.getMessage())
        }
        successMessage(Globals.STAGE)

        /**
        * Get the latest commit and retrive the username, email, and commit message for the COMMIT_* variables
        */
        Globals.STAGE='Workspace: Setup Environment'
        startMessage(Globals.STAGE)
        withCredentials([string(credentialsId: "${SCM_PASS_TOKEN}", variable: 'SCM_PASS')]) {
            try {
                sh "curl --silent -k https://gist.githubusercontent.com/paulbunyannet/99f759f8569fcffd217a03128f61bab4/raw > ${env.WORKSPACE}/github_latest_commit.php"
                sh "cd ${env.WORKSPACE}; php github_latest_commit.php --user=${Globals.SCM_OWNER} --pass=${SCM_PASS} --owner=${Globals.SCM_OWNER} --repo=${Globals.SCM_REPO} --sha=${Globals.SCM_BRANCH} --workspace=${env.WORKSPACE}"
                load "${env.WORKSPACE}/.env.git_latest_commit"
            } catch (error) {
                errorMessage(Globals.STAGE, error.getMessage())
            }
        }
        Globals.COMMIT_AUTHOR_EMAIL  = COMMIT_AUTHOR_EMAIL
        Globals.COMMIT_AUTHOR_NAME   = COMMIT_AUTHOR_NAME
        Globals.COMMIT_MESSAGE       = COMMIT_MESSAGE
        successMessage(Globals.STAGE, "last commit by '${Globals.COMMIT_AUTHOR_NAME}(${Globals.COMMIT_AUTHOR_EMAIL})' with message '${Globals.COMMIT_MESSAGE}'")

        /**
        * Pull down the latest version fo the repo from the given branch in SCM_BRANCH
        */
        Globals.STAGE='VCS: Git Pull'
        startMessage(Globals.STAGE, " from ${Globals.SCM_URL} ")
        try {
            checkout([$class: 'GitSCM', branches: [[name: "*/${Globals.SCM_BRANCH}"]], doGenerateSubmoduleConfigurations: false, extensions: [], submoduleCfg: [], userRemoteConfigs: [[credentialsId: SCM_CHECKOUT_TOKEN, url: SCM_URL]]])
        } catch (error) {
            errorMessage(Globals.STAGE, error.getMessage())
        }
        successMessage(Globals.STAGE)
        Globals.STAGE='Workspace: Setup Environment'
        startMessage(Globals.STAGE)
        withCredentials([string(credentialsId: "${SCM_PASS_TOKEN}", variable: 'SCM_PASS')]) {
            sh "rm -rf ${env.WORKSPACE}/groovy || true"
            sh "git clone https://paulbunyannet:${SCM_PASS}@github.com/paulbunyannet/groovy-scripts.git groovy"
        }
        successMessage(Globals.STAGE)

        /**
        * Get and check the locally stored env file and see if the keys all exist
        */
        Globals.STAGE='Environment: Composer installer'
        startMessage(Globals.STAGE)
        try {
            // composer install is required for the next stage....
            sh "curl --silent -k https://gist.githubusercontent.com/paulbunyannet/f896924537ec984ffaface03e4041000/raw > ${env.WORKSPACE}/cs.sh"
            sh "cd ${env.WORKSPACE}; bash cs.sh"
            echo "${Globals.WORKSPACE}";
            sh "cd ${Globals.WORKSPACE}; php composer.phar self-update"
            sh "cd ${Globals.WORKSPACE}; php composer.phar update --ignore-platform-reqs --no-scripts; php artisan clear-compiled; php artisan optimize"
            sh "cd ${Globals.WORKSPACE}; php composer.phar dump-autoload -o"
            sh "cd ${Globals.WORKSPACE}/tests/; mkdir _output"
            sh "cd ${Globals.WORKSPACE}/tests/; chmod 777 -R _output/"
            sh "rm -f ${Globals.WORKSPACE}/cs.sh"
        } catch (error) {
            errorMessage(Globals.STAGE, error.getMessage())
        }
        successMessage(Globals.STAGE)

        /**
        * Make .env from stored file and load into groovy
        */
        Globals.STAGE='Environment: Make .env'
        startMessage(Globals.STAGE)
        // Get stored .env file from credentials
        withCredentials([file(credentialsId: "${ENV_TOKEN}", variable: 'X_ENV_FILE_X')]) {
            // Try to copy the stored environment file to the current workspace
            echo "I will copy environment file to ${env.WORKSPACE} if it exists"
            // Copy the env file into the current workspace and fix it's permissions
            try {
                sh "cp ${X_ENV_FILE_X} ${env.WORKSPACE}/.env"
                sh "chmod 444 ${env.WORKSPACE}/.env"
                // Create groovy file version of the .env then fix below to make
                def envToGroovy=load "${env.WORKSPACE}/groovy/env-to-groovy.groovy"
                envToGroovy.envToGroovy("${env.WORKSPACE}/.env",  "${env.WORKSPACE}/.env.groovy")

            } catch (error) {
                errorMessage(Globals.STAGE, error.getMessage())
            }
        }
        try {
            sh "cd ${env.WORKSPACE}; composer env-check -- --actual=.env.example --expected=.env"
        } catch (error) {
            errorMessage(Globals.STAGE, error.getMessage())
        }
        successMessage(Globals.STAGE)

        /**
        * Set the folder premissions for folders that need to be written to
        */
        Globals.STAGE='Environment: fix folder permissions'
        startMessage(Globals.STAGE)
        try {
            for (i = 0; i <Globals.WRITABLE_DIRS.length; i++) {
                echo "Making writable directory ${env.WORKSPACE}/${Globals.WRITABLE_DIRS[i]}"
                sh "mkdir ${env.WORKSPACE}/${Globals.WRITABLE_DIRS[i]} || true"
                sh "chmod -fR 777 ${env.WORKSPACE}/${Globals.WRITABLE_DIRS[i]}";
            }
        } catch (error) {
            errorMessage(Globals.STAGE, error.getMessage())
        }
        successMessage(Globals.STAGE)

        Globals.STAGE='Git: Remote add origin'
        startMessage(Globals.STAGE)
        try {
            sh "cd ${Globals.WORKSPACE}; git remote add origin git@github.com:${SCM_OWNER}/${SCM_REPO}.git";
        } catch (error) {
                echo "There was no need to add origin, it was already added. Skipping this step."
        }
        successMessage(Globals.STAGE)

        Globals.STAGE='Git: Remote add upstream'
        startMessage(Globals.STAGE)
        try {
            if("${SCM_UPSTREAM_URL}" != "NONE"){
                sh "cd ${Globals.WORKSPACE}; git remote add upstream ${SCM_UPSTREAM_URL}";
            }else{
                echo "There was no upstream designated. Skipping this step."
            }
        } catch (error) {
            errorMessage(Globals.STAGE, error.getMessage())
        }
        successMessage(Globals.STAGE)

        /**
        * Get any docker image updates that may have been pushed using the current docker config
        */
        Globals.STAGE='Docker: Get any image updates'
        startMessage(Globals.STAGE)
        try {
            sh "cd ${env.WORKSPACE}; php composer.phar docker-assets";
            sh "cd ${env.WORKSPACE}; sh get_docker_assets.sh;"
            sh "cd ${Globals.WORKSPACE};docker-compose pull";
        } catch (error) {
            errorMessage(Globals.STAGE, error.getMessage())
        }
        successMessage(Globals.STAGE)

        /**
        * Start up the docker containers
        */
        Globals.STAGE='Docker: Start up containers'
        startMessage(Globals.STAGE)
        try {
            sh "cd ${Globals.WORKSPACE};docker-compose down -v"
            sh "cd ${Globals.WORKSPACE};composer docker-assets"
            sh "cd ${Globals.WORKSPACE};./docker-jenkins-start.sh";
        } catch (error) {
            errorMessage(Globals.STAGE, error.getMessage())
        }
        successMessage(Globals.STAGE)

        /**
        * Install Monolog
        */
        Globals.STAGE='Build: Install monolog/monolog globally'
        startMessage(Globals.STAGE)
        try {
            sh "cd ${env.WORKSPACE}; docker-compose exec -T code composer global require monolog/monolog"
        } catch (error) {
            errorMessage(Globals.STAGE, error.getMessage())
        }
        successMessage(Globals.STAGE)

        /**
        * Install backend assets
        */
        Globals.STAGE='Build: Install backend assets'
        startMessage(Globals.STAGE)
        try {
            // add any backend installers here....
            sh "chmod 444 ${env.WORKSPACE}/composer.json"
            sh "cd ${env.WORKSPACE}; docker-compose exec -T code composer install --no-progress --no-suggest -o"
            sh "cd ${env.WORKSPACE}; docker-compose exec -T code composer dump-autoload --optimize"
            sh "cd ${env.WORKSPACE}; docker-compose exec -T code composer update --no-progress --no-suggest -o"
        } catch (error) {
            errorMessage(Globals.STAGE, error.getMessage())
        }
        successMessage(Globals.STAGE)

        /**
        * Install front end assets
        */
        Globals.STAGE='Environment: Install frontend assets'
        startMessage(Globals.STAGE)
        try {
            // add any front end installers here....
            sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"yarn; bower install --allow-root; gulp\""
        } catch (error){
            errorMessage(Globals.STAGE, error.getMessage())
        }
        successMessage(Globals.STAGE)
    }

    /**
    * Run the test runner and see if all tests pass
    */
    stage('Dependencies') {
        Globals.STAGE='Tests: Run Dependencies before starting testing'
        startMessage(Globals.STAGE)
            //migrate before running anything
            sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"php artisan migrate\"";
            if (fileExists('yarn.lock')) {
                sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"yarn\"";
            }
            if (fileExists('gruntfile.js')) {
                sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"grunt\"";
            }
            if (fileExists('bower.json')) {
                sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"bower install --allow-root\"";
            }
            if (fileExists('gulpfile.js')) {
                sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"gulp\"";
            }
    }
    /**
    * Run the test runner and see if all tests pass
    */
    stage('Tests') {
        Globals.STAGE='Tests: Run tests'
        startMessage(Globals.STAGE)
        try {
            sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"vendor/bin/codecept run --coverage --coverage-xml --no-interaction\"";
        } catch (error) {
            sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"vendor/bin/codecept run --verbose --steps --debug --no-interaction -g failed\"";
            errorMessage(Globals.STAGE, error.getMessage())
        }

        successMessage(Globals.STAGE)
    }


    /**
    * Get the latest git log
    */
    stage('Prepare for deployment') {
        Globals.STAGE='Build: Generate Git log'
        startMessage(Globals.STAGE)
        try {
            echo "\u2605 Build: Generate Git log \u2605"
            sh "echo \$(git log -n 1 --pretty=format:\"%H\") > ${env.WORKSPACE}/temp/git_log"
            Globals.GIT_LOG=readFile("${env.WORKSPACE}/temp/git_log").trim()
            fileOperations([fileDeleteOperation(excludes: '', includes: 'git_log.txt')])
            fileOperations([fileCreateOperation(fileContent: "${Globals.GIT_LOG}", fileName: 'git_log.txt')])
        } catch (error) {
            errorMessage(Globals.STAGE, error.getMessage())
        }

        successMessage(Globals.STAGE)

        /**
        * Rebuild composer files for shipment off to production
        */
        Globals.STAGE='Build: Create production composer autoload'
        startMessage(Globals.STAGE)
        try {
            sh "cd ${Globals.WORKSPACE}; docker-compose exec -T code composer update --no-dev"
            sh "cd ${Globals.WORKSPACE}; docker-compose exec -T code composer dump-autoload --no-dev --optimize"
        } catch (error) {
            errorMessage(Globals.STAGE, error.getMessage())
        }

        successMessage(Globals.STAGE)

        stage('Notification') {
            /**
            * Let Rollbar know about the latest deployment
            */
            // Globals.STAGE='Deployment: Rollbar notification'
            // startMessage(Globals.STAGE)
            // try {
            //   withCredentials([string(credentialsId: "${ROLLBAR_TOKEN}", variable: 'X_ROLLBAR_DEPLOY_TOKEN_X')]) {
            //     sh "composer rollbar-deploy -- ${X_ROLLBAR_DEPLOY_TOKEN_X} ${Globals.SCM_BRANCH}"
            //   }
            // } catch (error) {
            //     warningMessage(Globals.STAGE, error.getMessage())
            // }
            // successMessage(Globals.STAGE)


            /**
            * Notify Mattermost that the build passed
            */

            Globals.STAGE='Deployment: Mattermost notification'
            startMessage(Globals.STAGE)
            try {
                mattermostSend "![${currentBuild.result}](https://jenkins.paulbunyan.net:8443/buildStatus/icon?job=${env.JOB_NAME} 'Icon') ${currentBuild.result} ${env.JOB_NAME} # ${env.BUILD_NUMBER} (<${env.BUILD_URL}|Open Pipe>)(<${env.BUILD_URL}/console|Open Console>)"
            } catch (error) {
                warningMessage(Globals.STAGE, error.getMessage())
            }
            successMessage(Globals.STAGE)
        }

        stage('Push to master') {

            Globals.STAGE='Deployment: git push to master'

            def commit_v = new Date().format( 'yyyy.MM.dd.HH.mm.ss' );
            sh "cd ${Globals.WORKSPACE}; git fetch --all";
            sh "cd ${Globals.WORKSPACE}; git stash";
            sh "cd ${Globals.WORKSPACE}; git checkout -b master --track origin/master";
            try {
                sh "cd ${Globals.WORKSPACE}; git pull origin master";
                //if there is an error that means that the changes push didnt have the last changes from the main branch so you should pull the main branch and resolve all issues first :)
            } catch (error) {
                sh "echo 'if there is an error that means that the changes push didnt have the last changes from the main branch so you should pull the main branch and resolve all issues first :)'";
                warningMessage(Globals.STAGE, error.getMessage())
            }
            sh "cd ${Globals.WORKSPACE}; git stash";
            sh "cd ${Globals.WORKSPACE}; git tag ${commit_v}";
            sh "cd ${Globals.WORKSPACE}; git merge origin/develop --commit -v -m 'tests passed on Jenkins'";
            sh "cd ${Globals.WORKSPACE}; ssh-agent sh -c 'ssh-add /var/lib/jenkins/.ssh/id_rsa; git push git@github.com:${SCM_OWNER}/${SCM_REPO}.git master'"
            if("${SCM_UPSTREAM_URL}" != "NONE"){
                try{
                    sh "cd ${Globals.WORKSPACE}; git rebase origin/master";
                    sh "cd ${Globals.WORKSPACE}; git config pull.rebase true";
                    sh "cd ${Globals.WORKSPACE}; git config rebase.autoStash true";
                    sh "cd ${Globals.WORKSPACE}; git request-pull ${SCM_UPSTREAM_URL} develop";
                }catch(error){
                    echo "I dont really care about this error."
                }
            }


        }
        // ====================================================================================
        // This will create an archive of all the source files needed to deploy to the server
        // ====================================================================================
        stage('Archive') {

            Globals.STAGE='Build: Creating Archive for Deployment'
            try {
                sh "cd ${env.WORKSPACE}; composer install"

                startMessage(Globals.STAGE)

                // ====================================================================================
                // Create an archive of the current SCM branch and save it to a tar.gz
                // ====================================================================================


                echo "Create build directory if it does not exist"
                Globals.BUILD_FOLDER = "build-${BUILD_NUMBER}"
                def archiveFolder = new File("${env.WORKSPACE}/${Globals.BUILD_FOLDER}")
                // If it doesn't exist
                if( !archiveFolder.exists() ) {
                    archiveFolder.mkdirs()
                }

                echo "Create a archive of the current build in ${archiveFolder}"
                sh "cd ${env.WORKSPACE}; git archive --format=tar.gz -o ${archiveFolder}/${Globals.BUILD_FOLDER}.tar.gz HEAD"

                // extract new build file and remove the current tar.gz to add the other folders
                sh "cd ${archiveFolder}; tar -zxf ${Globals.BUILD_FOLDER}.tar.gz; rm -f ${Globals.BUILD_FOLDER}.tar.gz"

                // ====================================================================================
                // Loop over the deployment.nonignore file (if it exists)
                // and look if those folders are in the workspace. If they are
                // then copy them to the archive folder.
                // ====================================================================================


                echo "Add in the non ignored folders to the archive"
                def nonIgnoresFile = new File("${env.WORKSPACE}/deployment.nonignore")
                if (nonIgnoresFile.exists()) {
                    def nonIgnoreLines = nonIgnoresFile.readLines()
                    nonIgnoreLines.each { String nonIgnored ->
                        // if folder exist locally then add it to the archive
                        sh "if [ -d ${env.WORKSPACE}/${nonIgnored} ]; then cp -r ${env.WORKSPACE}/${nonIgnored}/ ${archiveFolder}/; fi;"
                    }
                }

                // recombine the new copied and the archived file from git into a new archived
                sh "cd ${env.WORKSPACE}; tar --remove-files -zcf ${Globals.BUILD_FOLDER}.tar.gz ${archiveFolder}/;"
                if( !archiveFolder.exists() ) {
                    archiveFolder.mkdirs()
                }
                sh "cd ${env.WORKSPACE}; mv ${Globals.BUILD_FOLDER}.tar.gz ${archiveFolder}"

                // ====================================================================================
                // Now we have an archive that can be sent to the server.
                // folder structure for deployment files in tar is WORKSPACE/build-BUILD_NUMBER/
                // on the other side we'll rsync from that folder once deployment has taken place.
                // ====================================================================================

            } catch(error) {
                errorMessage(Globals.STAGE, error.getMessage())
            }
            successMessage(Globals.STAGE)
        }

        stage('Tear down') {
            /**
            * Tear down the docker containers for this build
            */
            Globals.STAGE='Docker: Bring containers down'
            startMessage(Globals.STAGE)
            sh "cd ${Globals.WORKSPACE}; docker-compose down -v";
            successMessage(Globals.STAGE)
        }

    }
}