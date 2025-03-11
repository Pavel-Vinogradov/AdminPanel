pipeline {
    agent any

    environment {
        // Окружение для переменных
        APP_ENV = 'production'
        DEPLOY_SERVER = 'your-server-ip-or-hostname'
        DEPLOY_USER = 'deploy-user'
        DEPLOY_PATH = '/path/to/deploy/folder'
        GIT_REPO = 'https://github.com/your-repo.git'
    }

    stages {
        stage('Checkout') {
            steps {
                echo 'Cloning repository...'
                git branch: 'main', url: "${GIT_REPO}" // Замените на ваш репозиторий
            }
        }

        stage('Check Code Style (PSR-12)') {
            steps {
                echo 'Checking code style against PSR-12...'
                sh '''
                    # Установка PHP CodeSniffer (если не установлен)
                    composer require squizlabs/php_codesniffer --dev

                    # Проверка соответствия PSR-12
                    vendor/bin/phpcs --standard=PSR12 --colors --ignore=vendor .

                    # Если есть ошибки, pipeline должен завершиться с ошибкой
                    if [ $? -ne 0 ]; then
                        echo "Code style check failed. Please fix the issues."
                        exit 1
                    fi
                '''
            }
        }

        stage('Run Tests') {
            steps {
                echo 'Running tests...'
                sh '''
                    # Установка зависимостей для тестов
                    composer install --no-interaction --prefer-dist --optimize-autoloader

                    # Запуск тестов PHPUnit
                    php artisan config:cache
                    vendor/bin/phpunit --coverage-text

                    # Если тесты не прошли, pipeline должен завершиться с ошибкой
                    if [ $? -ne 0 ]; then
                        echo "Tests failed. Please fix the issues."
                        exit 1
                    fi
                '''
            }
        }

        stage('Install Dependencies') {
            steps {
                echo 'Installing dependencies...'
                sh 'composer install --no-interaction --optimize-autoloader --no-dev'
                sh 'npm install'
                sh 'npm run build' // Сборка assets (если используете Vite или Mix)
            }
        }

        stage('Database Migrations') {
            steps {
                echo 'Running database migrations...'
                withEnv(["APP_ENV=${APP_ENV}", "DB_HOST=your-db-host", "DB_DATABASE=your-db-name", "DB_USERNAME=your-db-user", "DB_PASSWORD=your-db-password"]) {
                    sh 'php artisan migrate --force'
                }
            }
        }

        stage('Deploy to Server') {
            steps {
                echo 'Deploying to server...'
                sshagent(['your-ssh-key-id']) { // Замените на ID вашего SSH-ключа в Jenkins
                    sh """
                        rsync -avz --delete ./ ${DEPLOY_USER}@${DEPLOY_SERVER}:${DEPLOY_PATH}
                        ssh ${DEPLOY_USER}@${DEPLOY_SERVER} "cd ${DEPLOY_PATH} && chmod -R 775 storage bootstrap/cache"
                    """
                }
            }
        }

        stage('Clear Cache') {
            steps {
                echo 'Clearing cache...'
                sshagent(['your-ssh-key-id']) {
                    sh "ssh ${DEPLOY_USER}@${DEPLOY_SERVER} 'cd ${DEPLOY_PATH} && php artisan config:clear && php artisan cache:clear && php artisan view:clear'"
                }
            }
        }

        stage('Notify') {
            steps {
                echo 'Deployment completed successfully!'
                mail to: 'your-email@example.com', subject: 'Deployment Successful', body: 'The application has been deployed successfully.'
            }
        }
    }

    post {
        failure {
            echo 'Deployment failed!'
            mail to: 'your-email@example.com', subject: 'Deployment Failed', body: 'The deployment process has failed. Please check the logs.'
        }
    }
}
