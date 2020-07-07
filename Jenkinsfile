pipeline {

    agent any

    options {
        skipDefaultCheckout(true)
    }
    stages {

        stage('Checkout GitHub') {
            steps{
                cleanWs()
                script {
                    try {
                        git([url: 'https://github.com/unbmaster/redis', branch: 'master'])
                    } catch (Exception e) {
                    sh "echo $e; exit 1"
                    }
                }
            }
        }

        stage('Deploy Redis') {
            steps{

                script {
                    sh 'docker service rm redis || true'
                    try {
                        sh 'docker service create \
                              --name redis \
                              --mode replicated \
                              --replicas 1 \
                              --network app-net \
                              --endpoint-mode dnsrr \
                              --mount type=bind,src=/var/lib/jenkins/workspace/${JOB_NAME},dst=/usr/local/etc/redis/,ro=true \
                              unbmaster/redis:1.0'

                    } catch (Exception e) {
                        sh "echo $e; exit 1"
                        currentBuild.result = 'ABORTED'
                        error('Erro')
                    }
                }
            }
        }


        stage('Mock data') {
            steps{

                script {
                    sh 'docker service rm mock || true'
                    try {
                        sh 'docker service create \
                              --name mock \
                              --mode replicated \
                              --replicas 1 \
                              --network app-net \
                              --endpoint-mode dnsrr \
                              unbmaster/mock:1.0'
                    } catch (Exception e) {
                        sh "echo $e; exit 1"
                    }

                    sleep 5


                    sh 'docker exec -i $(docker container ls | grep mock | cut -d" " -f1) php ./var/www/docker/mock/login-redis.php'

                    sleep 5
                    sh 'docker service rm mock || true'
                }
            }
        }



    }
}