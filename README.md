# Simple php project to test code coverage with sonarqube

This is a simple PHP project to demonstrate how to set up code coverage with PHPUnit and SonarQube.

## Setup

1. Install dependencies:
   ```bash
   composer install
   ```

2. Run tests with code coverage:
   ```bash
   vendor/bin/phpunit --coverage-clover build/logs/clover.xml
   ```
