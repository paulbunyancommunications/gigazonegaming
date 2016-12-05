
node {

    /**
     * Check out project from source control
     */
   stage('Checkout') {
        checkout([$class: 'GitSCM', branches: [[name: '*/develop']], doGenerateSubmoduleConfigurations: false, extensions: [[$class: 'RelativeTargetDirectory', relativeTargetDir: 'scm_checkout']], submoduleCfg: [], userRemoteConfigs: [[credentialsId: 'a90cc198-6371-4211-8f0e-4344197a9fc1', url: 'https://github.com/paulbunyannet/gigazonegaming.git']]])
        sh 'yes | cp -R ${WORKSPACE}/scm_checkout/. ${WORKSPACE}/'
        deleteDir('scm_checkout')

    }

    /**
     * Create the decrypt password file to decrypt encoded files in 'Install Assets' stage
     */

    stage('Decrypt password') {
        withCredentials([string(credentialsId: 'gigazone-gaming-decode-code', variable: 'decrypt_password')]) {
            writeFile file: '.enc-pass', text: decrypt_password
        }
    }


    /**
    * Run install
    * if the 'jenkinsInstallComplete.txt' file exist that means that the install was already run.
    * Still grab assets and and run gulp if install already ran
    */

    stage('Install Assets') {
        def jenkinsInstallComplete = fileExists 'jenkinsInstallComplete.txt'
        if(!jenkinsInstallComplete) {
            sh 'jenkins-install.sh'
        } else {

            // still make sure that enc files are decrypted
            withCredentials([string(credentialsId: '083e48b1-8bab-4937-87b8-833e6afdcf68', variable: 'decrypt_password')]) {
                sh 'bash ${WORKSPACE}/decrypt-files.sh -w "${WORKSPACE}" -p "${decrypt_password}"'
            }

            // check status of vagrant box
            sh 'echo $(vagrant status) >> ${WORKSPACE}/vagrantStatus.txt'
            def vagrantStatus = readFile 'vagrantStatus.txt'
            if(!vagrantStatus.contains('running')) {
                sh 'vagrant provision'
                sh 'vagrant up'
                vagrantStatus.delete()
            }


            // run composer
            sh 'vagrant ssh -c "cd /var/www; php composer.phar update"'

            // run npm
            sh 'vagrant ssh -c "cd /var/www; npm update"'

            // run bower
            sh 'vagrant ssh -c "cd /var/www; bower update"'

            // run copy libraries
            sh 'vagrant ssh -c "cd /var/www; npm run-script copy-libraries;"'

            // run gulp
            sh 'vagrant ssh -c "cd /var/www; gulp"'

        }
    }

    /**
     * Run Tests
     */

    stage('Assertion Tests') {
        sh 'vagrant ssh -c "cd /var/www; php codecept.phar run acceptance -f -v"'
    }



    stage('Functional Tests') {
        sh 'vagrant ssh -c "cd /var/www; php codecept.phar run functional -f -v"'
    }



    stage('Integration Tests') {
        sh 'vagrant ssh -c "cd /var/www; php codecept.phar run intigration -f -v"'
    }



    stage('Unit Tests') {
        sh 'vagrant ssh -c "cd /var/www; php codecept.phar run unit -f -v"'
    }
