pipeline {
    agent any
    environment {
        UBUNTU_IP   = '192.168.10.17'
        UBUNTU_USER = 'linux-admin01'
        APP_NAME    = 'brasilburger'
        DOCKER_TAG  = "${env.BUILD_NUMBER}"
        SSH_KEY     = 'C:\\Users\\admin1\\.ssh\\id_ed25519'
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
                bat "ssh -i %SSH_KEY% -o StrictHostKeyChecking=no %UBUNTU_USER%@%UBUNTU_IP% \"rm -rf /home/linux-admin01/app && mkdir -p /home/linux-admin01/app\""
                bat "tar --exclude='.git' --exclude='obj' --exclude='bin' -czf app.tar.gz ."
                bat "scp -i %SSH_KEY% -o StrictHostKeyChecking=no app.tar.gz %UBUNTU_USER%@%UBUNTU_IP%:/home/linux-admin01/app/"
                bat "ssh -i %SSH_KEY% -o StrictHostKeyChecking=no %UBUNTU_USER%@%UBUNTU_IP% \"cd /home/linux-admin01/app && tar -xzf app.tar.gz && rm app.tar.gz\""
                bat "del app.tar.gz"
            }
        }
        stage('Docker Build') {
            steps {
                echo '=== Construction image Docker sur Ubuntu ==='
                bat "ssh -i %SSH_KEY% -o StrictHostKeyChecking=no %UBUNTU_USER%@%UBUNTU_IP% \"cd /home/linux-admin01/app && docker build --no-cache -t %APP_NAME%:%DOCKER_TAG% . && docker tag %APP_NAME%:%DOCKER_TAG% %APP_NAME%:latest && docker save %APP_NAME%:latest -o /tmp/%APP_NAME%.tar && sudo k3s ctr images import /tmp/%APP_NAME%.tar && rm /tmp/%APP_NAME%.tar\""
            }
        }
        stage('Deploy Kubernetes') {
            when { expression { return true } }
            steps {
                echo '=== Déploiement sur Kubernetes ==='
                bat "ssh -i %SSH_KEY% -o StrictHostKeyChecking=no %UBUNTU_USER%@%UBUNTU_IP% \"export KUBECONFIG=/home/linux-admin01/.kube/config && kubectl apply -f /home/linux-admin01/app/kubernetes/ && kubectl set image deployment/csharp-web csharp-web=docker.io/library/%APP_NAME%:latest && kubectl rollout status deployment/csharp-web\""
            }
        }
    }
    post {
        success { echo '=== ✅ Pipeline réussi ! ===' }
        failure  { echo '=== ❌ Échec — consultez les logs ===' }
    }
}