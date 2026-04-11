pipeline {
    agent any
    environment {
        UBUNTU_IP   = '192.168.2.22'
        UBUNTU_USER = 'devops'
        APP_NAME    = 'csharp-web'
        DOCKER_TAG  = "${env.BUILD_NUMBER}"
        SSH_KEY = 'C:\\Users\\svc-jenkins\\.ssh\\id_ed25519'
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
                bat """
                    ssh -i %SSH_KEY% -o StrictHostKeyChecking=no %UBUNTU_USER%@%UBUNTU_IP% "mkdir -p /home/devops/app"
                    scp -i %SSH_KEY% -o StrictHostKeyChecking=no -r . %UBUNTU_USER%@%UBUNTU_IP%:/home/devops/app/
                """
            }
        }

        stage('Docker Build') {
            steps {
                echo '=== Construction image Docker sur Ubuntu ==='
                bat """
                    ssh -i %SSH_KEY% -o StrictHostKeyChecking=no %UBUNTU_USER%@%UBUNTU_IP% "cd /home/devops/app && docker build -t %APP_NAME%:%DOCKER_TAG% . && docker tag %APP_NAME%:%DOCKER_TAG% %APP_NAME%:latest"
                """
            }
        }

        stage('Deploy Kubernetes') {
            when { branch 'csharp' }
            steps {
                echo '=== Déploiement sur Kubernetes ==='
                bat """
                    ssh -i %SSH_KEY% -o StrictHostKeyChecking=no %UBUNTU_USER%@%UBUNTU_IP% "kubectl apply -f /home/devops/app/kubernetes/ && kubectl set image deployment/csharp-web csharp-web=%APP_NAME%:%DOCKER_TAG% && kubectl rollout status deployment/csharp-web"
                """
            }
        }
    }

    post {
        success { echo '=== ✅ Pipeline réussi ! ===' }
        failure  { echo '=== ❌ Échec — consultez les logs ===' }
    }
}