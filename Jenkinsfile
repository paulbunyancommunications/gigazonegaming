import java.text.SimpleDateFormat

class Globals {

   static DATE_FORMAT_HUMAN           = new SimpleDateFormat("EEEE',' MMMM dd',' YYYY 'at' HH:mm:ss z");
   static DATE_FORMAT_LOGS            = new SimpleDateFormat("yyyy-MM-dd");
   static DATE_FORMAT_STAMP           = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
   static DATE_JOB_STARTED            = new Date();
   static String[] CONTAINERS                = [];
   static String STAGE                = "Job Started";
   static String GIT_LOG              = "";
   static String BUILD_FOLDER              = "";
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
def warningMesssage(String stage, String message = "", timestamp = Globals.DATE_JOB_STARTED) {
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
    def JOB_FAILED_DATE = new Date();
    // Create the error message body
    def JOB_FAILED_BODY = "Build for ${env.JOB_NAME} ${currentBuild.number} ${currentBuild.currentResult}! \n Console output: ${env.BUILD_URL}/console \n Stage \"${stage}\" failed on ${Globals.SCM_OWNER}/${Globals.SCM_REPO}:${Globals.SCM_BRANCH}.\n Stage \"${stage}\" was run on ${Globals.DATE_FORMAT_HUMAN.format(JOB_FAILED_DATE)}\n\n";
    //for (i = 0; i <$(docker-compose config --services).length; i++) {
    //sh "cd ${Globals.WORKSPACE};";
    //sh "${ echo \"\$(docker-compose config --services).trim()\"}";
    //sh "cd ${Globals.WORKSPACE};  echo \"$(docker-compose config --services).trim() > Globals.CONTAINERS"; ";
    //echo "THIS IS A CONTAINER ${Globals.CONTAINERS[1]}";
    //}
    // Get the log outputfrom the code container
    sh "cd ${Globals.WORKSPACE}; echo \"\$(docker-compose logs --tail ${Globals.TAIL_LENGTH} --timestamps code || true)\" | dd of=${Globals.WORKSPACE}/storage/logs/code.log"

    // Get the log outputfrom the firefox container
    sh "cd ${Globals.WORKSPACE}; echo \"\$(docker-compose logs --tail ${Globals.TAIL_LENGTH} --timestamps firefox  || true)\" | dd of=${Globals.WORKSPACE}/storage/logs/firefox.log"

    // Get the log outputfrom the web container
    sh "cd ${Globals.WORKSPACE}; echo \"\$(docker-compose logs --tail ${Globals.TAIL_LENGTH} --timestamps web  || true)\" | dd of=${Globals.WORKSPACE}/storage/logs/web.log"

    // Get the log outputfrom the hub container
    sh "cd ${Globals.WORKSPACE}; echo \"\$(docker-compose logs --tail ${Globals.TAIL_LENGTH} --timestamps hub  || true)\" | dd of=${Globals.WORKSPACE}/storage/logs/hub.log"

    // Bring container down and destroy
    sh "cd ${Globals.WORKSPACE}; docker-compose down -v";

    // Zip the output folder for email
    zip dir: "${Globals.WORKSPACE}/storage/logs", glob: '', zipFile: "${Globals.WORKSPACE}/${Globals.ARCHIVE_NAME}-test-output.zip"

    // email teh recipient the log output folder
    emailext attachmentsPattern: "${Globals.ARCHIVE_NAME}-test-output.zip", body: JOB_FAILED_BODY, subject: "Build for ${env.JOB_NAME} ${currentBuild.number} ${currentBuild.currentResult}!", to: "${Globals.COMMIT_AUTHOR_EMAIL}"
    // notify mattermost of this error
    //mattermostSend "![${currentBuild.currentResult}](https://jenkins.paulbunyan.net:8443/buildStatus/icon?job=${env.JOB_NAME} 'Icon') ${currentBuild.currentResult} ${env.JOB_NAME} # ${env.BUILD_NUMBER} (<${env.BUILD_URL}|Open Pipe>)(<${env.BUILD_URL}/console|Open Console>)"

  } catch (error) {
    // if there was an error return it here as a warning.
    warningMesssage(stage, error.getMessage())

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
            sh "curl --silent -k https://gist.githubusercontent.com/${Globals.SCM_OWNER}/99f759f8569fcffd217a03128f61bab4/raw > ${env.WORKSPACE}/github_latest_commit.php"
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
      sh "git clone https://${Globals.SCM_OWNER}:${SCM_PASS}@github.com/${Globals.SCM_OWNER}/groovy-scripts.git groovy"
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
      echo "1";
      echo "${Globals.WORKSPACE}";
      echo "2";
      sh "cd ${Globals.WORKSPACE}; php composer.phar update --ignore-platform-reqs --no-scripts; php artisan clear-compiled; php artisan optimize"
      echo "3";
      sh "cd ${Globals.WORKSPACE}; php composer.phar dump-autoload -o"
      echo "4";
      sh "cd ${Globals.WORKSPACE}/tests/; mkdir _output"
      echo "5";
      sh "cd ${Globals.WORKSPACE}/tests/; chmod 777 -R _output/"
      echo "8";
      sh "cd ${Globals.WORKSPACE}; php composer.phar update --ignore-platform-reqs"
      echo "9";
      sh "rm -f ${Globals.WORKSPACE}/cs.sh"
      echo "10";
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

    /**
    * Get any docker image updates that may have been pushed using the current docker config
    */

    Globals.STAGE='Docker: Get any image updates'
    startMessage(Globals.STAGE)
    try {
      sh "cd ${env.WORKSPACE}; php composer.phar docker-assets";
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
      sh "cd ${Globals.WORKSPACE};docker-compose down -v"
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
      sh "cd ${Globals.WORKSPACE};docker-compose down -v"
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
      sh "cd ${env.WORKSPACE}; docker-compose exec -T code composer install"
      sh "cd ${env.WORKSPACE}; docker-compose exec -T code composer dump-autoload --optimize"

    } catch (error) {
      sh "cd ${Globals.WORKSPACE}; docker-compose down -v"
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
      sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"yarn; bower install --allow-root\""
    } catch (error){
      sh "cd ${Globals.WORKSPACE}; docker-compose down -v"
      errorMessage(Globals.STAGE, error.getMessage())
    }
    successMessage(Globals.STAGE)

  }

  /**
  * Run the test runner and see if all tests pass
  */
  stage('Tests') {
    Globals.STAGE='Tests: Run tests'
    startMessage(Globals.STAGE)
    try {
      echo "App environment: ${APP_ENV}"
      switch(APP_ENV.toString()) {
        case "production":
          sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"composer test -- -f --ext DotReporter --coverage --coverage-html --coverage-xml\""
          break
        default:
          sh "cd ${env.WORKSPACE}; docker-compose exec -T code bash -c \"composer test -- -f -v --coverage --coverage-html --coverage-xml\""
          break
      }
    } catch (error) {
      failJob(Globals.STAGE, error.getMessage())
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
        sh "cd ${Globals.WORKSPACE}; docker-compose down -v";
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
        sh "cd ${Globals.WORKSPACE}; docker-compose down -v";
        errorMessage(Globals.STAGE, error.getMessage())
    }
    successMessage(Globals.STAGE)


    /**
    * Archive files for deployment
    */

    Globals.STAGE='Build: Archive files for deployment'

    startMessage(Globals.STAGE)
        try {
          Globals.BUILD_FOLDER = "build-${BUILD_NUMBER}"
          // Zip up the curent directory
          zip dir: '', glob: '', zipFile: "${Globals.BUILD_FOLDER}.zip"

            // unzip folder into build folder
          unzip dir: "${Globals.BUILD_FOLDER}", glob: '', zipFile: "${Globals.BUILD_FOLDER}.zip"
          // get rud of the archive
          sh "rm -f ${env.WORKSPACE}/${Globals.BUILD_FOLDER}.zip"

          // do any cleanups to build directory here
          // files
          sh "rm -f ${env.WORKSPACE}/${Globals.BUILD_FOLDER}/.env"
          sh "rm -f ${env.WORKSPACE}/${Globals.BUILD_FOLDER}/c3_error.log || true"
          sh "rm -f ${env.WORKSPACE}/${Globals.BUILD_FOLDER}/Dockerfile"
          sh "find ${env.WORKSPACE}/${Globals.BUILD_FOLDER} -name \"dock-*\" -type f -delete"
            //"database", "groovy", "temp", "storage", "cache", "mailings", "tests/_output", "css", "js", "tests"
          // folders
          sh "rm -rf ${env.WORKSPACE}/${Globals.BUILD_FOLDER}/groovy"
          sh "rm -rf ${env.WORKSPACE}/${Globals.BUILD_FOLDER}/tests"
          sh "rm -rf ${env.WORKSPACE}/${Globals.BUILD_FOLDER}/temp"
          sh "rm -rf ${env.WORKSPACE}/${Globals.BUILD_FOLDER}/node_modules"
          echo "a1";
          sh "rm -rf ${env.WORKSPACE}/${Globals.BUILD_FOLDER}/database"
          echo "a2";
          
        } catch (error) {
          echo "a3";
            sh "cd ${Globals.WORKSPACE};docker-compose down -v";
          echo "a4";
            errorMessage(Globals.STAGE, error.getMessage())
          echo "a5";
        }

          echo "a6";

    echo "Artifacts copied to ${env.WORKSPACE}/${Globals.BUILD_FOLDER}"

          echo "a7";
    successMessage(Globals.STAGE)
          echo "a8";
  }

  /**
  * Deploy archived files to the server over ssh
  */
  /**
  * stage('Deploy') {
  *    Globals.STAGE='Deployment: Deploy files to remote server'
  *    startMessage(Globals.STAGE)
  *    try {

  *      sshagent(["${SSH_TOKEN}"]) {
  *        // check if .env file exists on the remote host
  *        // https://stackoverflow.com/a/18290318/405758
  *        sh returnStatus: true, script: "ssh -p ${SSH_PORT} ${SSH_USER}@${SSH_SERVER} stat ${SSH_PATH}/.env"
  *        sh "scp -P ${SSH_PORT} ${SSH_USER}@${SSH_SERVER}:${SSH_PATH}/.env ${env.WORKSPACE}/temp"
  *        // check if the remote .env and the local have all the environment keys
  *        sh "cd ${env.WORKSPACE}; composer env-check -- --actual=temp/.env --expected=.env"
  *        //sh "ssh -p ${SSH_PORT} -vvv -o StrictHostKeyChecking=no ${SSH_USER}@${SSH_SERVER} uname -a"
  *        /**sh "ssh user@server rm -rf /var/www/temp_deploy/dist/"
  *        sh "ssh user@server mkdir -p /var/www/temp_deploy"
  *        sh "scp -r dist user@server:/var/www/temp_deploy/dist/"
  *        sh "ssh user@server “rm -rf /var/www/example.com/dist/ && mv /var/www/temp_deploy/dist/ /var/www/example.com/"
  *        */
  /**      }


  *    } catch (error) {
  *      sh "cd ${Globals.WORKSPACE};docker-compose down -v";
   *     errorMessage(Globals.STAGE, error.getMessage())
  *    }
  *    successMessage(Globals.STAGE)
  *  }
  */

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
  //     warningMesssage(Globals.STAGE, error.getMessage())
  // }
  // successMessage(Globals.STAGE)


  /**
  * Notify Mattermost that the build passed
  */

  // Globals.STAGE='Deployment: Mattermost notification'
  // startMessage(Globals.STAGE)
  // try {
  //   mattermostSend "![${currentBuild.currentResult}](https://jenkins.paulbunyan.net:8443/buildStatus/icon?job=${env.JOB_NAME} 'Icon') ${currentBuild.currentResult} ${env.JOB_NAME} # ${env.BUILD_NUMBER} (<${env.BUILD_URL}|Open Pipe>)(<${env.BUILD_URL}/console|Open Console>)"
  // } catch (error) {
  //   warningMesssage(Globals.STAGE, error.getMessage())
  // }
  // successMessage(Globals.STAGE)
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