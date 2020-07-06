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
                              --config source=redis-conf,target=/etc/redis/redis.conf,mode=0400 \
                              --mount type=bind,src=/var/lib/jenkins/workspace/${JOB_NAME},dst=/usr/local/etc/redis/,ro=true \
                              redis:6.0.5'

                    } catch (Exception e) {
                        sh "echo $e; exit 1"
                        currentBuild.result = 'ABORTED'
                        error('Erro')
                    }
                }
            }
        }






    }
}