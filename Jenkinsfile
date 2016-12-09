#!/usr/bin/env groovy
retry(2) {

    node {

        currentBuild.result = "SUCCESS"

        try {

            /**
             * Check out project from source control
             */
            stage('Info') {

                echo "\u2605 BUILD_URL=${env.BUILD_URL} \u2605"
                echo "\u2605 WORKSPACE=${env.WORKSPACE} \u2605"

            }

            /**
             * Check out project from source control
             */
            stage('Checkout') {

                def scm_url = 'https://github.com/paulbunyannet/gigazonegaming.git'
                echo "\u2605 Checking out project from ${scm_url} \u2605"
                checkout([$class: 'GitSCM', branches: [[name: '*/develop']], doGenerateSubmoduleConfigurations: false, extensions: [], submoduleCfg: [], userRemoteConfigs: [[credentialsId: 'a90cc198-6371-4211-8f0e-4344197a9fc1', url: "${scm_url}"]]])

            }

            /**
             * Create the decrypt password file to decrypt encoded files in 'Install Assets' stage
             */
            stage('Decrypt Credential') {

                echo "\u2605 Decrypting credential and config files from repository \u2605"
                def latestBashPackageCommitHash = sh (

                    script: "echo \$(git ls-remote https://github.com/paulbunyannet/bash.git | grep HEAD | awk '{ print \$1}')",
                    returnStdout: true
                    ).trim()
                sh "wget -N \"https://raw.githubusercontent.com/paulbunyannet/bash/${latestBashPackageCommitHash}/setup/files/decrypt-files.sh\" -O ${env.WORKSPACE}/decrypt-files.sh"

                withCredentials([string(credentialsId: 'gigazone-gaming-decode-code', variable: 'decrypt_password')]) {
                    //writeFile file: '.enc-pass', text: decrypt_password
                    sh (
                        script:"bash ${env.WORKSPACE}/decrypt-files.sh -w ${env.WORKSPACE} -p ${decrypt_password}"
                        )
                }

            }

            /**
             * Fix phing config files
             */
           stage('Fix Phing Config Files') {
                echo "\u2605 Fix phing config files \u2605"

                def build_dir = "${env.WORKSPACE}/build/config"
                List jenkins_phing_configs = ["${build_dir}/jenkins.config", "${build_dir}/hosts/jenkins.host"]
                for (String jenkins_phing_config : jenkins_phing_configs) {
                    sh "sed 's/production/jenkins/' ${jenkins_phing_config} >/dev/null"
                }
           }

            /**
            * Run Cleanup of Vagrant environment
            */
            stage('Vagrant Cleanup') {

                def box_name = 'gigazonegaming.local'
                echo "\u2605 Run Cleanup of Vagrant environment for ${box_name} \u2605"

                sh "VBoxManage controlvm ${box_name} poweroff || echo '${box_name} was not powered off, it might not have existed.'"
                sh "VBoxManage unregistervm ${box_name} --delete || echo '${box_name} was not deleted, it might not have existed.'"
                sh "rm -rf '/var/lib/jenkins/VirtualBox VMs/${box_name}' || echo '/var/lib/jenkins/VirtualBox VMs/${box_name} was not deleted, it might not have existed.'"
                sh "vagrant destroy"
                sh "vagrant box update"


           }

           /**
            * Fix vagrant file config path
            */
           stage('Fix Vagrantfile') {

                echo "\u2605 Fix Vagrantfile so it will use the correct config file \u2605"
                sh "sed 's/config-custom.yaml/config-jenkins.yaml/' ${env.WORKSPACE}/Vagrantfile >/dev/null"

           }

           /**
            * Download tools
            */

           stage('Download Tools') {

                echo "\u2605 Download tools needed for later \u2605"
                // get composer.phar
                sh "wget -q -N https://getcomposer.org/composer.phar -O ${env.WORKSPACE}/composer.phar"
                // get c3.phar
                sh "wget -q -N https://raw.github.com/Codeception/c3/2.0/c3.php -O ${env.WORKSPACE}/c3.php"
                // get codecept.phar
                sh "wget -q -N http://codeception.com/codecept.phar -O ${env.WORKSPACE}/codecept.phar"

           }

           /**
            * Boot Up Vagrant box
            */
           stage('Boot Vagrant Box') {
                echo "\u2605 Booting up Vagrant box \u2605"
                sh "vagrant up"

           }

            /**
             * Check Vagrant status
             */
           stage('Check Vagrant Status') {
                def vagrant_status = sh (
                    script: 'vagrant status',
                    returnStdout: true
                )
                echo "\u2605 Vagrant status: ${vagrant_status} \u2605"
           }

            /**
             * Install NPM modules though Yarn
             */
           stage('NPM') {
                echo "\u2605 Installing NPM libraries through Yarn \u2605"
                sh "vagrant ssh -c \"sudo npm install -g yarn; cd /var/www; yarn install\""

           }

            /**
             * Install Composer modules
             */
           stage('Composer') {
                echo "\u2605 Installing Composer dependencies \u2605"
                sh "vagrant ssh -c \"cd /var/www; php composer.phar install\""
           }

            /**
             * Install Bower modules
             */
           stage('Bower') {
                echo "\u2605 Installing Bower dependencies \u2605"
                sh "vagrant ssh -c \"sudo npm install -g bower; cd /var/www; bower install\""
           }

            /**
             * Copying script to required places
             */
           stage('Node Copy') {
                echo "\u2605 Copying script to required places \u2605"
                sh "vagrant ssh -c \"cd /var/www; npm run-script copy-libraries\""
           }


            /**
             * Compile scripts with Gulp
             */
           stage('Gulp') {
                echo "\u2605 Copying script to required places \u2605"
                sh "vagrant ssh -c \"sudo npm install -g gulp; cd /var/www; gulp\""
           }

            /**
             * Compile scripts with Gulp
             */
           stage('Clean Wordpress wp folder') {
                def wp_folder = "${env.WORKSPACE}/public_html/wp"
                echo "\u2605 Cleaning ${wp_folder} folder \u2605"
                sh "rm -rf ${wp_folder}/wp-content"
                sh "rm -f ${wp_folder}/wp-config-sample.php"
                sh "rm -f ${wp_folder}/.htaccess"

           }


            /**
             * create cache dir if not already existing
             */
           stage('Make Cache Directory') {
                echo "\u2605 Create cache dir if not already existing \u2605"
                sh "vagrant ssh -c \"cd /var/www; mkdir -m 0770 cache || echo ''\""
           }


            /**
             * generate new Laravel app key
             */
           stage('Generate App Key') {
                echo "\u2605 Generate new Laravel app key \u2605"
                sh "vagrant ssh -c \"cd /var/www; php artisan key:generate;\""
           }

            /**
             * generate new Laravel app key
             */
           stage('Generate Wp Keys') {
                echo "\u2605 Generate new Wordpress app keys \u2605"
                sh "vagrant ssh -c \"cd /var/www; php artisan wp:keys --file=.env;\""
           }

            /**
             * Migrate dbs
             */
           stage('Run Migration') {
                echo "\u2605 Run DB migrations \u2605"
                sh "vagrant ssh -c \"cd /var/www; php artisan migrate\""
           }

            /**
             * Preping testing environment
             */
           stage('Prep testing environment') {
                echo "\u2605 Prep testing environment \u2605"
                sh "vagrant ssh -c \"cd /var/www; php codecept.phar clean && php codecept.phar build\" >/dev/null 2>&1"
           }

            /**
             * Run Assertion Tests
             */

            stage('Assertion Tests') {
                def test_started = sh (
                    script: "date +'%Y-%m-%d %H:%M:%S'",
                    returnStdout: true
                )
                echo "\u2605 Running Assertion Tests, started at ${test_started} \u2605"
                sh 'vagrant ssh -c "cd /var/www; php codecept.phar run acceptance -f -v"'
            }

            /**
             * Run Functional Tests
             */

            stage('Functional Tests') {
                def test_started = sh (
                    script: "date +'%Y-%m-%d %H:%M:%S'",
                    returnStdout: true
                )
                echo "\u2605 Running Functional Tests, started at ${test_started} \u2605"
                sh 'vagrant ssh -c "cd /var/www; php codecept.phar run functional -f -v"'
            }

            /**
             * Run Integration Tests
             */

            stage('Integration Tests') {
                def test_started = sh (
                    script: "date +'%Y-%m-%d %H:%M:%S'",
                    returnStdout: true
                )
                echo "\u2605 Running Integration Tests, started at ${test_started} \u2605"
                sh 'vagrant ssh -c "cd /var/www; php codecept.phar run integration -f -v"'
            }

            /**
             * Run Unit Tests
             */
            stage('Unit Tests') {
                def test_started = sh (
                    script: "date +'%Y-%m-%d %H:%M:%S'",
                    returnStdout: true
                )
                echo "\u2605 Running Unit Tests, started at ${test_started} \u2605"
                sh 'vagrant ssh -c "cd /var/www; php codecept.phar run unit -f -v"'
            }

            /**
             * Build successful, send out an email to the one who prompted the job
             */
            stage('success') {

                wrap([$class: 'BuildUser']) {
                    def email = BUILD_USER_EMAIL
                    def first_name = BUILD_USER_FIRST_NAME

                    def groovyDomain = fileLoader.fromGit(
                        'domain-name-from-url.groovy',
                        'https://github.com/paulbunyannet/groovy-scripts.git',
                        'master',
                        null,
                        ''
                    )

                    def domain = groovyDomain.domainNameFromUrl("${env.JENKINS_URL}")

                    def groovyNiceDuration = fileLoader.fromGit(
                        'nice-duration.groovy',
                        'https://github.com/paulbunyannet/groovy-scripts.git',
                        'master',
                        null,
                        ''
                    )

                    def duration = groovyNiceDuration.niceDuration("${currentBuild.timeInMillis}")

                    mail body: "Hi ${first_name}, The project build was successful for job ${env.JOB_NAME} (build number ${currentBuild.number})!\n\rThe job took ${duration} to build.",
                                from: "notify@${domain}",
                                replyTo: "notify@${domain}",
                                subject: "Project build successful for job ${env.JOB_NAME}",
                                to: "${email}"
                }

            }

        } catch(Exception e) {

            /**
             * Job failed, send out a message with the failure
             */
            wrap([$class: 'BuildUser']) {

                def email = env.BUILD_USER_EMAIL
                def first_name = env.BUILD_USER_FIRST_NAME
                def user = env.BUILD_USER_ID
                def groovyDomain = fileLoader.fromGit(
                    'domain-name-from-url.groovy',
                    'https://github.com/paulbunyannet/groovy-scripts.git',
                    'master',
                    null,
                    ''
                )

                def domain = groovyDomain.domainNameFromUrl("${env.JENKINS_URL}")
                withCredentials([usernamePassword(credentialsId: "${user}-api-access", passwordVariable: 'token', usernameVariable: 'username')]) {
                    def result_cmd = "curl -u ${username}:${token} \"${env.JENKINS_URL}job/${env.JOB_NAME}/lastBuild/consoleText\""
                    def result = sh (
                        script: "${result_cmd}",
                        returnStdout: true
                    ).trim()


                mail body: "Oh no ${first_name}, the project build for ${env.JOB_NAME} (build number ${currentBuild.number}) was unsuccessful. \n\rSee the output here: ${currentBuild.absoluteUrl}\n\rConsole Log Output:\n\r${result}" ,
                     from: "notify@${domain}",
                     replyTo: "notify@${domain}",
                     subject: "Project build error for ${env.JOB_NAME}",
                     to: "${email}"
                }
            }

            throw err
        }
    }
}