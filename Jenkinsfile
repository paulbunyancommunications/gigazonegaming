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
    static String SCM_AUTH_URL         = "";
    static String SCM_UPSTREAM_URL     = "";
    static String SCM_UPSTREAM_AUTH_URL= "";
    static String SCM_OWNER            = "";
    static String SCM_REPO             = "";
    static String SSH_SERVER             = "";
    static String SSH_PORT             = "";
    static String SSH_TOKEN             = "";
    static String SCM_BRANCH           = "develop";
    static String COMMIT_AUTHOR_EMAIL  = "example@example.com";
    static String COMMIT_AUTHOR_NAME   = "John Doe";
    static String COMMIT_MESSAGE       = "Commit message";
    /* Array of directories to make writable */
    static String[] WRITABLE_DIRS      = [];
    static String[] testingSuites   =   [];
}


/*
 * startMessage
 * Output a formatted start message message
 * @prop String stage
 * @prop String message
 * @prop mixed timestamp
 */
def startMessage(String stage, String message = "", timestamp = Globals.DATE_JOB_STARTED) {
    echo "\n############################################################################\n############################################################################\n"
    println "\u2605 Started ${stage} ${message} at ${Globals.DATE_FORMAT_STAMP.format(timestamp)} \u2605"
    echo "############################################################################"
}

/* Output a formatted success message */
def successMessage(String stage, String message = "", timestamp = Globals.DATE_JOB_STARTED) {
    echo "\n############################################################################\n############################################################################\n"
    println "\u2713 ${stage} completed ${message} at ${Globals.DATE_FORMAT_STAMP.format(timestamp)} \u263A!"
    echo "############################################################################"
}

/* Output a formatted warning message */
def warningMessage(String stage, String message = "", timestamp = Globals.DATE_JOB_STARTED) {
    echo "\n############################################################################\n############################################################################\n"
    println "\u2297 ${stage} warning: ${message} at ${Globals.DATE_FORMAT_STAMP.format(timestamp)} \u1F631!"
    echo "############################################################################"
}

/* output a formatted error message */
def errorMessage(String stage, String message = "", timestamp = Globals.DATE_JOB_STARTED) {
    echo "\n############################################################################\n############################################################################"
    println "\u2297 ${stage} failed: ${message} \u1F631!"
    echo "############################################################################"
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
        //sh "cd ${env.WORKSPACE};";
        //sh "${ echo \"\$(docker-compose config --services).trim()\"}";
        //sh "cd ${env.WORKSPACE};  echo \"$(docker-compose config --services).trim() > Globals.CONTAINERS"; ";
        //echo "THIS IS A CONTAINER ${Globals.CONTAINERS[1]}";
        //}
        // Get the log outputfrom the code container
        sh "cd ${env.WORKSPACE}; docker-compose logs --timestamps code > ./tests/_output/code.log"

        // Get the log outputfrom the firefox container
        sh "cd ${env.WORKSPACE}; docker-compose logs --timestamps firefox > ./tests/_output/firefox.log"

        // Get the log outputfrom the web container
        sh "cd ${env.WORKSPACE}; docker-compose logs --timestamps web > ./tests/_output/web.log"

        // Get the log outputfrom the hub container
        sh "cd ${env.WORKSPACE}; docker-compose logs --timestamps hub > ./tests/_output/hub.log"

        // Zip the output folder for email
        zip dir: "${env.WORKSPACE}/tests/_output", glob: '', zipFile: "${env.WORKSPACE}/${Globals.ARCHIVE_NAME}-test-output.zip"



        // email teh recipient the log output folder
        emailext attachmentsPattern: "${Globals.ARCHIVE_NAME}-test-output.zip", body: JOB_FAILED_BODY, subject: "Build for ${env.JOB_NAME} ${currentBuild.number} ${RESULT}!", to: "${Globals.COMMIT_AUTHOR_EMAIL}"

        // notify mattermost of this error
        mattermostSend "![${RESULT}](https://jenkins.paulbunyan.net:8443/buildStatus/icon?job=${env.JOB_NAME} 'Icon') ${RESULT} ${env.JOB_NAME} # ${env.BUILD_NUMBER} (<${env.BUILD_URL}|Open Pipe>)(<${env.BUILD_URL}/console|Open Console>)"

        // Bring container down and destroy
        sh "cd ${env.WORKSPACE}; docker-compose down -v";

    } catch (error) {
        // if there was an error return it here as a warning.
        warningMessage(stage, error.getMessage())
    }
    // fail the job
    return error("${stage} failed: ${message} on ${Globals.DATE_FORMAT_STAMP.format(timestamp)}")
}

node {
    echo "${env.WORKSPACE}"
    Globals.DATE_JOB_STARTED = new Date()
    env.WORKSPACE = env.WORKSPACE
    Globals.SSH_TOKEN = SSH_TOKEN
    Globals.SSH_SERVER = SSH_SERVER
    Globals.SSH_PORT = SSH_PORT
    Globals.SCM_OWNER = SCM_OWNER
    Globals.SCM_REPO = SCM_REPO
    Globals.SCM_BRANCH = SCM_BRANCH
    try{
        echo "\n\n setting origin URL"
        Globals.SCM_URL =  "https://github.com/${SCM_OWNER}/${SCM_REPO}.git"
        echo "done setting origin URL"
        withCredentials([string(credentialsId: "${SCM_PASS_TOKEN}", variable: 'SCM_PASS')]) {
            echo "setting origin AUTH URL"
            Globals.SCM_AUTH_URL =  "https://${SCM_OWNER}:${SCM_PASS}@github.com/${SCM_OWNER}/${SCM_REPO}.git"
            echo "done setting origin AUTH URL"
        }
    } catch(error){
        echo "there was a missing variable, SCM_URL wasn't set"
    }

    if("${SCM_UPSTREAM_OWNER_GIT}" != "NONE"){
    try{
        echo "setting UPSTREAM URL"
        Globals.SCM_UPSTREAM_URL = "https://github.com/${SCM_UPSTREAM_OWNER_GIT}/${SCM_REPO}.git"
        echo "done setting UPSTREAM URL"
        withCredentials([string(credentialsId: "${SCM_UPSTREAM_PASS_TOKEN}", variable: 'SCM_UPSTREAM_PASS')]) {
            echo "setting UPSTREAM AUTH URL"
            Globals.SCM_UPSTREAM_AUTH_URL = "https://${SCM_UPSTREAM_OWNER}:${SCM_UPSTREAM_PASS}@github.com/${SCM_UPSTREAM_OWNER_GIT}/${SCM_REPO}.git"
            echo "done setting UPSTREAM AUTH URL\n\n"
        }
    } catch(error){
        echo "there was a missing variable, SCM_UPSTREAM_URL wasn't set"
    }
    }else{
    Globals.SCM_UPSTREAM_URL = "NONE"
    }
    Globals.ARCHIVE_NAME="${env.JOB_NAME}-${env.BUILD_NUMBER}-${Globals.SCM_BRANCH}"
    Globals.WRITABLE_DIRS = ["database", "groovy", "temp", "storage", "cache", "mailings", "tests/_output", "css", "js", "tests"] as String[]
    Globals.testingSuites = ["acceptace","functional","integration", "static_analysis","unit"] as String[]

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

        /**n
        * Get the latest commit and retrieve the username, email, and commit message for the COMMIT_* variables
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
            checkout([$class: 'GitSCM', branches: [[name: "*/${Globals.SCM_BRANCH}"]], doGenerateSubmoduleConfigurations: false, extensions: [], submoduleCfg: [], userRemoteConfigs: [[credentialsId: SCM_CHECKOUT_TOKEN, url: Globals.SCM_URL]]])
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
            echo "${env.WORKSPACE}";
            sh "cd ${env.WORKSPACE}; php composer.phar self-update"
            sh "cd ${env.WORKSPACE}; php composer.phar update --ignore-platform-reqs --no-scripts; php artisan clear-compiled; php artisan optimize"
            sh "cd ${env.WORKSPACE}; php composer.phar dump-autoload -o"
            sh "cd ${env.WORKSPACE}/tests/; mkdir _output"
            sh "cd ${env.WORKSPACE}/tests/; chmod 777 -R _output/"
            sh "rm -f ${env.WORKSPACE}/cs.sh"
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
        * Set the folder permissions for folders that need to be written to
        */
        Globals.STAGE='Environment: fix folder permissions'
        startMessage(Globals.STAGE)
        try {
            for (i = 0; i < Globals.WRITABLE_DIRS.length; i++) {
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
        sh "cd ${env.WORKSPACE}; git remote -v";
        try {
            echo "adding origin auth."
            sh "cd ${env.WORKSPACE}; git remote add origin ${Globals.SCM_AUTH_URL}"
        } catch (error) {
                    echo "adding origin without auth."
                    try {
                        sh "cd ${env.WORKSPACE}; git remote set-url origin ${Globals.SCM_AUTH_URL}";
                    } catch (error2) {
                        try {
                            sh "cd ${env.WORKSPACE}; git remote add origin ${Globals.SCM_URL}";
                        } catch (error3) {
                            echo "remote already existed."
                        }
                    }
            echo "There was no need to add origin, it was already added. Skipping this step."
        }
        successMessage(Globals.STAGE)

        Globals.STAGE='Git: Remote add upstream'
        startMessage(Globals.STAGE)
        try {
            echo "going to try to set upstream"
            echo "${Globals.SCM_UPSTREAM_URL}"
            echo "${Globals.SCM_UPSTREAM_AUTH_URL}"
            if("${Globals.SCM_UPSTREAM_URL}" != "NONE"){
                echo "trying to set upstream"
                try {
                    echo "adding upstream auth."
                    sh "cd ${env.WORKSPACE}; git remote add upstream ${Globals.SCM_UPSTREAM_AUTH_URL}";
                } catch (error) {
                    try {
                        echo "setting upstream with auth."
                        sh "cd ${env.WORKSPACE}; git remote set-url upstream ${Globals.SCM_UPSTREAM_AUTH_URL}";
                    } catch (error2) {
                        try {
                            echo "adding upstream without auth."
                            sh "cd ${env.WORKSPACE}; git remote add upstream ${Globals.SCM_UPSTREAM_URL}";
                        } catch (error3) {
                            echo "remote already existed. Going to set-url instead"
                        }
                    }
                }
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
            sh "cd ${env.WORKSPACE};docker-compose pull";
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
            sh "cd ${env.WORKSPACE};docker-compose down -v"
            sh "cd ${env.WORKSPACE};composer docker-assets"
            sh "cd ${env.WORKSPACE};./docker-jenkins-start.sh";
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
            sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"yarn; bower install --allow-root; yarn run gulp production\""
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
        def gulp='true'
        if (fileExists('yarn.lock')) {
            sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"yarn\"";
            if (fileExists('gulpfile.js')) {
                sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"yarn run gulp production\"";
                gulp='false'
            }
        }
        /**
        *if (fileExists('gruntfile.js')) {
        *    if (fileExists('yarn.lock')) {
        *        sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"yarn add grunt\"";
        *    }else{
        *        sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"npm install grunt\"";
        *    }
        *    sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"grunt\"";
        *}
        */
        if (fileExists('bower.json')) {
            sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"bower install --allow-root\"";
        }
        if (gulp=='true') {
            if (fileExists('gulpfile.js')) {
                sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"gulp\"";
            }
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
            for (i = 0; i < Globals.testingPaths.length; i++) {
                def testFolder = new File("${env.WORKSPACE}/tests/${Globals.testingSuites[i]}")
                // If it doesn't exist
                if( testFolder.exists() ) {
                    try {
                        Globals.STAGE2='Tests: Run ${Globals.testingSuites[i]} tests'
                        startMessage(Globals.STAGE2)
                        sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"vendor/bin/codecept run ${env.WORKSPACE}/tests/${Globals.testingSuites[i]} --verbose --steps --debug --no-interaction\"";
                    } catch (error3) {
                        def myError = error.getMessage();
                        echo "--------------------------------------------------------------";
                        echo "--------------------------------------------------------------";
                        echo "There was an error -------------------------------------------";
                        echo "${myError}";
                        echo "--------------------------------------------------------------";
                        echo "--------------------------------------------------------------";
                    }
                 }
            }
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
    }
    /**
    * Rebuild composer files for shipment off to production
    */
    stage('Prepare for production composer autoload') {
    Globals.STAGE='Build: Create production composer autoload'
    startMessage(Globals.STAGE)
    try {
        sh "cd ${env.WORKSPACE}; docker-compose exec -T code composer update --no-dev"
        sh "cd ${env.WORKSPACE}; docker-compose exec -T code composer dump-autoload --no-dev --optimize"
    } catch (error) {
        errorMessage(Globals.STAGE, error.getMessage())
    }

    successMessage(Globals.STAGE)
    }
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
        sh "cd ${env.WORKSPACE}; git fetch --all";
        sh "cd ${env.WORKSPACE}; git stash";
        sh "cd ${env.WORKSPACE}; git checkout -b master --track origin/master";
        sh "cd ${env.WORKSPACE}; git merge origin/develop";
        sh "cd ${env.WORKSPACE}; git stash";
        sh "cd ${env.WORKSPACE}; git tag ${commit_v}";
        sh "cd ${env.WORKSPACE}; git push ${Globals.SCM_AUTH_URL} master"
        //sh "cd ${env.WORKSPACE}; ssh-agent sh -c 'ssh-add /var/lib/jenkins/.ssh/id_rsa; git push ${Globals.SCM_URL} master'"
        if("${Globals.SCM_UPSTREAM_URL}" != "NONE"){
            try{
                sh "cd ${env.WORKSPACE}; git rebase origin/master";
                sh "cd ${env.WORKSPACE}; git config pull.rebase true";
                sh "cd ${env.WORKSPACE}; git config rebase.autoStash true";
                //sh "cd ${env.WORKSPACE}; git request-pull ${Globals.SCM_UPSTREAM_AUTH_URL} develop";
                sh "cd ${env.WORKSPACE}; git checkout -b develop --track upstream/develop";
                sh "cd ${env.WORKSPACE}; git merge origin/develop";
                sh "cd ${env.WORKSPACE}; git push ${Globals.SCM_UPSTREAM_AUTH_URL} develop";
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
            startMessage(Globals.STAGE)
            sh "cd ${env.WORKSPACE}; composer install --ignore-platform-reqs --no-scripts; php artisan optimize"


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

            withCredentials([sshUserPrivateKey(credentialsId: "${Globals.SSH_TOKEN}", keyFileVariable: 'key', passphraseVariable: '', usernameVariable: 'username')]) {
                // some block
                String SERVER_ACCOUNT = "${username}@${Globals.SSH_SERVER}"
                string PUT_DIR = "FROM_JENKINS"
                string FILE_TAR = "${Globals.BUILD_FOLDER}.tar.gz"
                String D_ROOT_TO= "/home/${username}"
                String TO_WO_FILE = "${D_ROOT_TO}/${PUT_DIR}"
                String TO_WITH_FILE = "/home/${username}/${PUT_DIR}/${Globals.BUILD_FOLDER}.tar.gz"
                String FROM_WO_FILE = "${env.WORKSPACE}/${Globals.BUILD_FOLDER}"
                String FROM_WITH_FILE = "${env.WORKSPACE}/${Globals.BUILD_FOLDER}/${FILE_TAR}"
                sh "cd ${env.WORKSPACE}; ssh -i \"${key}\" -p 85 ${SERVER_ACCOUNT} \"rm -rf ${TO_WO_FILE}; mkdir -p ${TO_WO_FILE}\"";
                sh "cd ${env.WORKSPACE}; scp -i \"${key}\" -P ${Globals.SSH_PORT} ${FROM_WITH_FILE} ${SERVER_ACCOUNT}:${TO_WITH_FILE}";
                sh "cd ${env.WORKSPACE}; ssh -i \"${key}\" -p 85 ${SERVER_ACCOUNT} \"cd ${PUT_DIR};  tar -xvzf ${FILE_TAR}; cp -a ${TO_WO_FILE}/${FROM_WO_FILE}/. ${D_ROOT_TO}/; rm -rf ${TO_WO_FILE};\"";
                sh "cd ${env.WORKSPACE}; ssh -i \"${key}\" -p 85 ${SERVER_ACCOUNT} \"ls -la\"";
                sh "cd ${env.WORKSPACE}; ssh -i \"${key}\" -p 85 ${SERVER_ACCOUNT} \"php artisan migrate\"";
                sh "cd ${env.WORKSPACE}; ssh -i \"${key}\" -p 85 ${SERVER_ACCOUNT} \"ls -la\"";
            }

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
        sh "cd ${env.WORKSPACE}; docker-compose down -v";
        successMessage(Globals.STAGE)
    }


}