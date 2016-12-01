/**
* Check out project from source control
*/
node {
   stage('Checkout') {
        checkout([$class: 'GitSCM', branches: [[name: '*/develop']], doGenerateSubmoduleConfigurations: false, extensions: [], submoduleCfg: [], userRemoteConfigs: [[credentialsId: 'a90cc198-6371-4211-8f0e-4344197a9fc1', url: 'https://github.com/paulbunyannet/gigazonegaming.git']]])
    }
}

/**
* Create the decrypt password file to decrypt encoded files in 'Install Assets' stage
*/
node {
    stage('Decrypt password') {
        withCredentials([string(credentialsId: '083e48b1-8bab-4937-87b8-833e6afdcf68', variable: 'decrypt_password')]) {
            writeFile file: '.enc-pass', text: decrypt_password
        }
    }
}

/**
* Run install
* if the 'jenkinsInstallComplete.txt' file exist that means that the install was already run.
* Still grab assets and and run gulp if install already ran
*/
node {
    stage('Install Assets') {
        Path jenkinsInstallComplete = Paths.get('jenkinsInstallComplete.txt')
        if(!${Files.exists(jenkinsInstallComplete)}) {
            sh 'bash jenkins-install.sh'
        } else {

            // still make sure that enc files are decrypted
            withCredentials([string(credentialsId: '083e48b1-8bab-4937-87b8-833e6afdcf68', variable: 'decrypt_password')]) {
                sh 'bash ${WORKSPACE}/decrypt-files.sh -w "${WORKSPACE}" -p "${decrypt_password}"'
            }

            // check status of vagrant box
            sh 'echo $(vagrant status) >> ${WORKSPACE}/vagrantStatus.txt'
            def vagrantStatus = new File('${WORKSPACE}/vagrantStatus.txt')
            if(!vagrantStatus.getText('UTF-8').contains('running')) {
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
}


node {

    stage('Assertion Tests') {
        sh 'vagrant ssh -c "cd /var/www; php codecept.phar run acceptance -f -v"'
    }
}


node {

    stage('Functional Tests') {
        sh 'vagrant ssh -c "cd /var/www; php codecept.phar run functional -f -v"'
    }
}


node {

    stage('Integration Tests') {
        sh 'vagrant ssh -c "cd /var/www; php codecept.phar run intigration -f -v"'
    }
}


node {

    stage('Unit Tests') {
        sh 'vagrant ssh -c "cd /var/www; php codecept.phar run unit -f -v"'
    }
}