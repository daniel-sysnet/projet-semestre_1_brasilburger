pipeline {
    agent any
    environment {
        UBUNTU_IP   = '192.168.2.22'
        UBUNTU_USER = 'devops'
        APP_NAME    = 'csharp-web'
        DOCKER_TAG  = "${env.BUILD_NUMBER}"
    }
    stages {

        stage('Checkout') {
            steps {
                echo '=== Récupération du code GitHub ==='
                checkout scm
            }
        }

        stage('Build C#') {
            steps {
                echo '=== Compilation .NET ==='
                bat 'dotnet restore ./csharp_web/csharp_web.csproj'
                bat 'dotnet build ./csharp_web/csharp_web.csproj --configuration Release --no-restore'
            }
        }

        stage('Transfert vers Ubuntu') {
            steps {
                echo '=== Envoi du code vers Ubuntu ==='
                withCredentials([sshUserPrivateKey(
                    credentialsId: 'ubuntu-ssh',
                    keyFileVariable: 'SSH_KEY',
                    usernameVariable: 'SSH_USER'
                )]) {
                    script {
                        def remote = [
                            name        : 'ubuntu-devops',
                            host        : env.UBUNTU_IP,
                            user        : env.UBUNTU_USER,
                            identityFile: env.SSH_KEY,
                            allowAnyHosts: true
                        ]
                        sshCommand remote: remote, command: "mkdir -p /home/devops/app"
                        sshPut remote: remote, from: '.', into: '/home/devops/app'
                    }
                }
            }
        }

        stage('Docker Build') {
            steps {
                echo '=== Construction image Docker sur Ubuntu ==='
                withCredentials([sshUserPrivateKey(
                    credentialsId: 'ubuntu-ssh',
                    keyFileVariable: 'SSH_KEY',
                    usernameVariable: 'SSH_USER'
                )]) {
                    script {
                        def remote = [
                            name        : 'ubuntu-devops',
                            host        : env.UBUNTU_IP,
                            user        : env.UBUNTU_USER,
                            identityFile: env.SSH_KEY,
                            allowAnyHosts: true
                        ]
                        sshCommand remote: remote, command: "cd /home/devops/app && docker build -t ${env.APP_NAME}:${env.DOCKER_TAG} . && docker tag ${env.APP_NAME}:${env.DOCKER_TAG} ${env.APP_NAME}:latest"
                    }
                }
            }
        }

        stage('Deploy Kubernetes') {
            when { branch 'csharp' }
            steps {
                echo '=== Déploiement sur Kubernetes ==='
                withCredentials([sshUserPrivateKey(
                    credentialsId: 'ubuntu-ssh',
                    keyFileVariable: 'SSH_KEY',
                    usernameVariable: 'SSH_USER'
                )]) {
                    script {
                        def remote = [
                            name        : 'ubuntu-devops',
                            host        : env.UBUNTU_IP,
                            user        : env.UBUNTU_USER,
                            identityFile: env.SSH_KEY,
                            allowAnyHosts: true
                        ]
                        sshCommand remote: remote, command: "kubectl apply -f /home/devops/app/kubernetes/ && kubectl set image deployment/csharp-web csharp-web=${env.APP_NAME}:${env.DOCKER_TAG} && kubectl rollout status deployment/csharp-web"
                    }
                }
            }
        }
    }

    post {
        success { echo '=== ✅ Pipeline réussi ! ===' }
        failure  { echo '=== ❌ Échec — consultez les logs ===' }
    }
}