
node {

    /**
     * Check out project from source control
     */
     stage('Checkout') {
        checkout([$class: 'GitSCM', branches: [[name: '*/develop']], doGenerateSubmoduleConfigurations: false, extensions: [], submoduleCfg: [], userRemoteConfigs: [[credentialsId: 'a90cc198-6371-4211-8f0e-4344197a9fc1', url: 'https://github.com/paulbunyannet/gigazonegaming.git']]])
     }
}

node {
    /**
     * Create the decrypt password file to decrypt encoded files in 'Install Assets' stage
     */

    stage('Decrypt password') {
        withCredentials([string(credentialsId: 'gigazone-gaming-decode-code', variable: 'decrypt_password')]) {
            writeFile file: '.enc-pass', text: decrypt_password
        }
    }
}

node{
    /**
    * Run install
    */

    stage('Install Assets') {
        sh 'bash ${WORKSPACE}/jenkins-install.sh'
    }
}

node {
    /**
     * Run Tests
     */

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