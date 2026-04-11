pipeline {
    agent any
    environment {
        UBUNTU_IP   = '192.168.2.22'   // ← remplace par l'IP réelle de ton Ubuntu
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
                sshPut remote: [
                    host: "${UBUNTU_IP}",
                    user: "${UBUNTU_USER}",
                    credentialsId: 'ubuntu-ssh',
                    allowAnyHosts: true
                ],
                from: '.',
                into: '/home/devops/app'
            }
        }

        stage('Docker Build') {
            steps {
                echo '=== Construction image Docker sur Ubuntu ==='
                sshCommand remote: [
                    host: "${UBUNTU_IP}",
                    user: "${UBUNTU_USER}",
                    credentialsId: 'ubuntu-ssh',
                    allowAnyHosts: true
                ],
                command: "cd /home/devops/app && docker build -t ${APP_NAME}:${DOCKER_TAG} . && docker tag ${APP_NAME}:${DOCKER_TAG} ${APP_NAME}:latest"
            }
        }

        stage('Deploy Kubernetes') {
            when { branch 'csharp' }
            steps {
                echo '=== Déploiement sur Kubernetes ==='
                sshCommand remote: [
                    host: "${UBUNTU_IP}",
                    user: "${UBUNTU_USER}",
                    credentialsId: 'ubuntu-ssh',
                    allowAnyHosts: true
                ],
                command: "kubectl apply -f /home/devops/app/kubernetes/ && kubectl set image deployment/csharp-web csharp-web=${APP_NAME}:${DOCKER_TAG} && kubectl rollout status deployment/csharp-web"
            }
        }
    }

    post {
        success { echo '=== ✅ Pipeline réussi ! ===' }
        failure  { echo '=== ❌ Échec — consultez les logs ===' }
    }
}