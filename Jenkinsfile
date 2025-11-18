pipeline {
    agent {
        kubernetes {
            // Define a pod with the containers needed for the build
            yaml """
apiVersion: v1
kind: Pod
spec:
  containers:
  - name: php
    image: php:8.1-cli
    command:
    - sleep
    args:
    - 99d
  - name: sonar-scanner
    image: sonarsource/sonar-scanner-cli
    command:
    - sleep
    args:
    - 99d
"""
        }
    }

    environment {
        // Assumes a credential with this ID is configured in Jenkins
        SONAR_TOKEN = credentials('sonar-test-project-dev') 
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
                // Execute commands inside the 'php' container
                container('php') {
                    sh """
                    # Install git and unzip, which are required by Composer
                    apt-get update && apt-get install -y git unzip
                    # Download and install Composer
                    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
                    # Install dependencies
                    composer install
                    """
                }
            }
        }

        // stage('Install Dependencies') {
        //     steps {
        //         // Use a PHP container to install dependencies
        //         script {
        //             docker.image('php:8.1-cli').inside {
        //                 sh 'composer install'
        //             }
        //         }
        //     }
        // }

        stage('Run Tests and Generate Coverage') {
            steps {
                // // Execute commands inside the 'php' container
                // container('php') {
                //     sh 'vendor/bin/phpunit --coverage-clover build/logs/clover.xml'
                // }

                // Execute commands inside the 'php' container
                container('php') {
                    catchError(buildResult: 'UNSTABLE', stageResult: 'UNSTABLE') {
                        sh """
                            pecl install xdebug && docker-php-ext-enable xdebug
                            export XDEBUG_MODE=coverage
                            // xdebug-enable
                            xdebug on
                            vendor/bin/phpunit --coverage-clover build/logs/clover.xml
                        """
                    }
                }
            }
        }
        // stage('Run Tests and Generate Coverage') {
        //     steps {
        //         // Use a PHP container to run tests and generate coverage report
        //         script {
        //             docker.image('php:8.1-cli').inside {
        //                 sh 'vendor/bin/phpunit --coverage-clover build/logs/clover.xml'
        //             }
        //         }
        //     }
        // }

        stage('SonarQube Scan') {
            steps {
                // Execute commands inside the 'sonar-scanner' container
                container('sonar-scanner') {
                    sh """
                        pwd
                        ls -al
                    """

                    sh """
                    sonar-scanner \\
                        -Dsonar.projectKey=test-project-dev \\
                        -Dsonar.sources=. \\
                        -Dsonar.host.url=https://sonarqube.dev.compliantcloud.com \\
                        -Dsonar.token=$SONAR_TOKEN \\
                        -Dsonar.php.coverage.reportPaths=build/logs/clover.xml
                    """

                    sh """
                        pwd
                        ls -al
                    """
                }
            }
        }

        // stage('SonarQube Scan') {
        //     steps {
        //         // Use the official SonarScanner CLI image
        //         script {
        //             docker.image('sonarsource/sonar-scanner-cli').inside {
        //                 // Run the SonarQube scanner
        //                 sh """
        //                 sonar-scanner \
        //                     -Dsonar.projectKey=test-project \
        //                     -Dsonar.sources=. \
        //                     -Dsonar.host.url=https://sonarqube.dev.compliantcloud.com \
        //                     -Dsonar.login=$SONAR_TOKEN \
        //                     -Dsonar.php.coverage.reportPaths=build/logs/clover.xml
        //                 """
        //             }
        //         }
        //     }
        // }
    }
}
