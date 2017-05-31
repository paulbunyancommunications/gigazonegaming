node {
    String branch = "develop"
    String emailMe = "castillo@paulbunyan.net"
    String archive_name = "${env.JOB_NAME}-${env.BUILD_NUMBER}-${branch}"
    /**
    * String scm_url = 'https://github.com/paulbunyancommunications/gigazonegaming.git'
    * String scm_url = 'https://github.com/castillo-n/gigazonegaming.git'
    * String scm_url = 'https://github.com/natenolting/gigazonegaming.git'
    * String scm_url = 'https://github.com/romanmartushev/gigazonegaming.git'
    */
    String scm_url = 'https://github.com/castillo-n/gigazonegaming.git'
    timestamps {
        /**
        * Check out project from source control
        */
        stage('clean-directory') {
            echo "WORKSPACE ${env.WORKSPACE}";
            echo "JOB_URL ${env.JOB_URL}";
            echo "BUILD_URL ${env.BUILD_URL}";
            echo "JOB_NAME ${env.JOB_NAME}";
            echo "JOB_BASE_NAME ${env.JOB_BASE_NAME}";
            echo "BUILD_DISPLAY_NAME ${env.BUILD_DISPLAY_NAME}";
            echo "BUILD_ID ${env.BUILD_ID}";
            echo "BUILD_NUMBER ${env.BUILD_NUMBER}";
            echo "CHANGE_AUTHOR_DISPLAY_NAME ${env.CHANGE_AUTHOR_DISPLAY_NAME}";
            echo "CHANGE_AUTHOR ${env.CHANGE_AUTHOR}";
            echo "CHANGE_TITLE ${env.CHANGE_TITLE}";
            echo "BRANCH_NAME ${env.BRANCH_NAME}";
            echo "currentBuild.currentResult ${currentBuild.currentResult}";
            try {
                sh "cd ${env.WORKSPACE}";
                step([$class: 'WsCleanup']);
            } catch(error) {
                echo "directory couldn't be clean before build";
                updateGitlabCommitStatus name: 'jenkins', state: "${currentBuild.currentResult.toLowerCase()}"
                error("Build failed because couldn't clean directory.")
            }
        }

        stage('git-pull') {
            echo "\u2605 Checking out project from ${scm_url} \u2605"
            try {
                checkout([$class: 'GitSCM', branches: [[name: "*/${branch}"]], doGenerateSubmoduleConfigurations: false, extensions: [], submoduleCfg: [], userRemoteConfigs: [[credentialsId: 'a90cc198-6371-4211-8f0e-4344197a9fc1', url: "${scm_url}"]]])
            } catch(error) {
                try {
                    checkout([$class: 'GitSCM', branches: [[name: "*/${branch}"]], doGenerateSubmoduleConfigurations: false, extensions: [], submoduleCfg: [], userRemoteConfigs: [[credentialsId: 'a90cc198-6371-4211-8f0e-4344197a9fc1', url: "${scm_url}"]]])
                } catch(error_b) {
                    updateGitlabCommitStatus name: 'jenkins', state: "${currentBuild.currentResult.toLowerCase()}"
                    error("Build failed on git pull.")

                }
            }
        }

        /**
        * start docker
        */
        stage('build:env') {
            echo "\u2605 Running DOCKER-START \u2605"
            sh "cd ${env.WORKSPACE}";
            sh "cp .env.example .env";
        }
        /**
        * start docker
        */
        stage('build:docker-assets') {
            echo "\u2605 Running DOCKER-START \u2605"
            try {
                sh "cd ${env.WORKSPACE}";
                sh "curl --silent https://raw.githubusercontent.com/paulbunyannet/bash/\$(git ls-remote https://github.com/paulbunyannet/bash.git | grep HEAD | awk '{ print \$1}')/docker/update_docker_assets_file.sh > update_docker_assets_file.sh";
                sh "chmod a+x update_docker_assets_file.sh";
                sh "./update_docker_assets_file.sh";
                sh "chmod a+x get_docker_assets.sh";
                sh "./get_docker_assets.sh"
            } catch(error) {
                sh "docker-compose down -v";
                sh "docker system prune -f"
                updateGitlabCommitStatus name: 'jenkins', state: "${currentBuild.currentResult.toLowerCase()}"
                error("Build failed because couldn't get docker assets.")
            }
        }

        /**
        * start docker
        */
        stage('check:load-variables') {
            echo "\u2605 checking variables were load \u2605"
            sh "cd ${env.WORKSPACE}";
            sh "./dock-helpers.sh";
        }

        /**
        * start docker
        */
        stage('docker:up') {
            echo "\u2605 Running DOCKER-START \u2605"
            sh "cd ${env.WORKSPACE}";
            sh "./dock-helpers.sh";
            try {
            sh "./docker-jenkins-start.sh"
            } catch(error) {
                try {
                    echo "\u2605 Rerunning CODECEPT RUN UNIT \u2605"
                    sh "docker-compose down -v";
                    sh "docker system prune -f"
                    sh "./docker-jenkins-start.sh"
                } catch(error_b) {
                    sh "docker-compose down -v";
                    sh "docker system prune -f"
                    updateGitlabCommitStatus name: 'jenkins', state: "${currentBuild.currentResult.toLowerCase()}"
                    error("Build failed because couldn't docker up.")
                }
            }
        }


        /**
        * test docker app all
        */
        stage('file-permissions') {
            echo "\u2605 Running Permissions \u2605";
            sh "cd ${env.WORKSPACE}";
            sh "ls";
            try {
                echo "\u2605 Fixing permissions \u2605"
                sh "chmod -fR 777 ${env.WORKSPACE}/storage";
                sh "chmod -fR 777 ${env.WORKSPACE}/public_html/wp-content/plugins/map-manager/js";
                sh "chmod -f 777 ${env.WORKSPACE}/c3_error.log";
            } catch(error_b) {
                sh "docker-compose down -v";
                sh "docker system prune -f"
                updateGitlabCommitStatus name: 'jenkins', state: "${currentBuild.currentResult.toLowerCase()}"
                error("Build failed because couldn't get file permissions changed.")
            }
        }
        /**
        * unit test docker app
        */
        stage('test:unit') {
            echo "\u2605 Running CODECEPT RUN UNIT \u2605"
            sh "cd ${env.WORKSPACE}";
            sh "./dock-helpers.sh";
            try {
                sh "docker-compose exec -T code codecept run tests/unit"
            } catch(error) {
                try {
                    echo "\u2605 Rerunning CODECEPT RUN UNIT \u2605"
                    sh "docker-compose exec -T code codecept run tests/unit"
                } catch(error_b) {
                    sh "docker-compose down -v";
                    sh "docker system prune -f"
                    updateGitlabCommitStatus name: 'jenkins', state: "${currentBuild.currentResult.toLowerCase()}"
                    error("Build failed because unit test didn't pass.")
                }
            }
        }
        /**
        * integration test docker app
        */
        stage('test:integration') {
            echo "\u2605 Running CODECEPT RUN INTEGRATION \u2605"
            sh "cd ${env.WORKSPACE}";
            sh "./dock-helpers.sh";
            try {
                sh "docker-compose exec -T code codecept run tests/integration"
            } catch(error) {
                try {
                    echo "\u2605 Rerunning CODECEPT RUN INTEGRATION \u2605"
                    sh "docker-compose exec -T code codecept run tests/integration"
                } catch(error_b) {
                    sh "docker-compose down -v";
                    sh "docker system prune -f"
                    updateGitlabCommitStatus name: 'jenkins', state: "${currentBuild.currentResult.toLowerCase()}"
                    error("Build failed because integration test didn't pass.")
                }
            }
        }
        /**
        * functional test docker app
        */
        stage('test:functional') {
            echo "\u2605 Running CODECEPT RUN FUNCTIONAL \u2605"
            sh "cd ${env.WORKSPACE}";
            sh "./dock-helpers.sh";
            try {
                sh "docker-compose exec -T code codecept run tests/functional"
            } catch(error) {
                try {
                    echo "\u2605 Rerunning CODECEPT RUN FUNCTIONAL \u2605"
                    sh "docker-compose exec -T code codecept run tests/functional"
                } catch(error_b) {
                    sh "docker-compose down -v";
                    sh "docker system prune -f"
                    updateGitlabCommitStatus name: 'jenkins', state: "${currentBuild.currentResult.toLowerCase()}"
                    error("Build failed because functional test didn't pass.")
                }
            }
        }
        /**
        * acceptance test docker app
        */
        stage('test:acceptance') {
            echo "\u2605 Running CODECEPT RUN ACCEPTANCE \u2605"
            sh "cd ${env.WORKSPACE}";
            sh "./dock-helpers.sh";
            try {
                sh "docker-compose exec -T code codecept run tests/acceptance"
            } catch(error) {
                try {
                    echo "\u2605 Rerunning CODECEPT RUN ACCEPTANCE \u2605"
                    sh "docker-compose exec -T code codecept run tests/acceptance"
                } catch(error_b) {
                    sh "docker-compose down -v";
                    sh "docker system prune -f"
                    updateGitlabCommitStatus name: 'jenkins', state: "${currentBuild.currentResult.toLowerCase()}"
                    error("Build failed because acceptance test didn't pass.")
                }
            }
        }

        stage('build:git_log') {
            try {
                echo "\u2605 Running git_log.sh to get current commit hash \u2605"
                sh "cd ${env.WORKSPACE}";
                sh "bash ${env.WORKSPACE}/git_log.sh"
            } catch(error_b) {
                echo "not git log was created"
            }
        }

        stage('build:archive') {
            echo "\u2605 Archiving artifacts \u2605"
            archiveArtifacts artifacts: '**/*', onlyIfSuccessful: true
        }
        stage('docker:down') {
            sh "docker-compose down -v";
            sh "docker system prune -f"
            updateGitlabCommitStatus name: 'jenkins', state: "${currentBuild.currentResult.toLowerCase()}"
        }
        stage('mattermost it'){
            echo "currentBuild.result ${currentBuild.result}";
            echo "currentBuild.currentResult ${currentBuild.currentResult}";
            mattermostSend "![${currentBuild.currentResult}](https://jenkins.paulbunyan.net:8443/buildStatus/icon?job=${env.JOB_NAME} 'Icon') ${currentBuild.currentResult} ${env.JOB_NAME} # ${env.BUILD_NUMBER} (<${env.BUILD_URL}|Open Pipe>)(<${env.BUILD_URL}/console|Open Console>)"
            step([$class: 'Mailer', notifyEveryUnstableBuild: true, recipients: "${emailMe}", sendToIndividuals: false])
        }
    }
}