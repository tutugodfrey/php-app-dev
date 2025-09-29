pipeline {
    agent any

    environment {
        // Assumes a credential with this ID is configured in Jenkins
        SONAR_TOKEN = credentials('SONAR_TOKEN') 
    }

    stages {
        stage('Checkout') {
            steps {
                // Checkout the repository
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                // Use a PHP container to install dependencies
                script {
                    docker.image('php:8.1-cli').inside {
                        sh 'composer install'
                    }
                }
            }
        }

        stage('Run Tests and Generate Coverage') {
            steps {
                // Use a PHP container to run tests and generate coverage report
                script {
                    docker.image('php:8.1-cli').inside {
                        sh 'vendor/bin/phpunit --coverage-clover build/logs/clover.xml'
                    }
                }
            }
        }

        stage('SonarQube Scan') {
            steps {
                // Use the official SonarScanner CLI image
                script {
                    docker.image('sonarsource/sonar-scanner-cli').inside {
                        // Run the SonarQube scanner
                        sh """
                        sonar-scanner \
                            -Dsonar.projectKey=test-project \
                            -Dsonar.sources=. \
                            -Dsonar.host.url=https://sonarqube.dev.compliantcloud.com \
                            -Dsonar.login=$SONAR_TOKEN \
                            -Dsonar.php.coverage.reportPaths=build/logs/clover.xml
                        """
                    }
                }
            }
        }
    }
}
