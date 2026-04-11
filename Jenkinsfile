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

        stage('Tests') {
            steps {
                echo '=== Tests unitaires ==='
                bat 'dotnet test --no-build --configuration Release'
            }
        }

        stage('Transfert vers Ubuntu') {
            steps {
                echo '=== Envoi du code vers Ubuntu ==='
                sshagent(['ubuntu-ssh']) {
                    bat """
                        ssh -o StrictHostKeyChecking=no %UBUNTU_USER%@%UBUNTU_IP% "mkdir -p /home/devops/app"
                        scp -r -o StrictHostKeyChecking=no . %UBUNTU_USER%@%UBUNTU_IP%:/home/devops/app/
                    """
                }
            }
        }

        stage('Docker Build') {
            steps {
                echo '=== Construction image Docker sur Ubuntu ==='
                sshagent(['ubuntu-ssh']) {
                    bat """
                        ssh -o StrictHostKeyChecking=no %UBUNTU_USER%@%UBUNTU_IP% ^
                        "cd /home/devops/app && docker build -t %APP_NAME%:%DOCKER_TAG% . && docker tag %APP_NAME%:%DOCKER_TAG% %APP_NAME%:latest"
                    """
                }
            }
        }

        stage('Deploy Kubernetes') {
            when { branch 'main' }
            steps {
                echo '=== Déploiement sur Kubernetes ==='
                sshagent(['ubuntu-ssh']) {
                    bat """
                        ssh -o StrictHostKeyChecking=no %UBUNTU_USER%@%UBUNTU_IP% ^
                        "kubectl apply -f /home/devops/app/kubernetes/ && kubectl set image deployment/csharp-web csharp-web=%APP_NAME%:%DOCKER_TAG% && kubectl rollout status deployment/csharp-web"
                    """
                }
            }
        }
    }

    post {
        success { echo '=== ✅ Pipeline réussi ! App déployée ===' }
        failure  { echo '=== ❌ Échec — consultez les logs ===' }
    }
}