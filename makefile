# Variables
DOCKER_COMPOSE = docker-compose
API_CONTAINER = api
DB_CONTAINER = mysql

# Phony targets
.PHONY: install sleep ps up up-recreate down forget db-shell api-build api-db api-key api-env api-config-cache api-composer-install api-shell api-root-shell api-test api-test-feature api-test-php-unit api-build-swagger api-passport-key api-passport-generate fix-permissions

# Install and setup the project
install: api-env api-composer-install api-build up sleep api-db api-key api-passport-key api-passport-generate

# Sleep for 3 seconds to allow services to start
sleep:
	sleep 3

# Show Docker container status
ps:
	$(DOCKER_COMPOSE) ps

# Start Docker containers
up:
	$(DOCKER_COMPOSE) up -d

# Recreate Docker containers
up-recreate:
	$(DOCKER_COMPOSE) up -d --force-recreate

# Stop and remove Docker containers
down:
	$(DOCKER_COMPOSE) down

# Stop and remove Docker containers, images, and volumes
forget:
	$(DOCKER_COMPOSE) down --rmi all --volumes @docker volume rm backend-test_sail-mysql 2>/dev/null
		|| echo "Volume not found"

# Access the MySQL shell
db-shell:
	mysql -h 127.0.0.1 -P 3306 -u sail -ppassword

# Build the API Docker container without cache
api-build:
	USER_ID=$(shell id -u) GROUP_ID=$(shell id -g) $(DOCKER_COMPOSE) build --no-cache

# Run database migrations and seed the database
api-db:
	$(DOCKER_COMPOSE) exec $(API_CONTAINER) php /var/www/html/artisan migrate:fresh
	$(DOCKER_COMPOSE) exec $(API_CONTAINER) php /var/www/html/artisan db:seed

# Generate the application key
api-key:
	$(DOCKER_COMPOSE) exec $(API_CONTAINER) php /var/www/html/artisan key:generate

# Copy the example environment file
api-env:
	cp .env.example .env

# Cache the configuration
api-config-cache:
	$(DOCKER_COMPOSE) exec $(API_CONTAINER) php /var/www/html/artisan config:cache

# Install Composer dependencies
api-composer-install:
	composer install --ignore-platform-reqs

# Access the API container shell as the sail user
api-shell:
	$(DOCKER_COMPOSE) exec $(API_CONTAINER) bash -c 'su sail'

# Access the API container shell as root
api-root-shell:
	$(DOCKER_COMPOSE) exec $(API_CONTAINER) bash

# Run all PHPUnit tests
api-test:
	$(DOCKER_COMPOSE) exec $(API_CONTAINER) php /var/www/html/artisan test

# Run PHPUnit Feature tests
api-test-feature:
	$(DOCKER_COMPOSE) exec $(API_CONTAINER) php /var/www/html/artisan test --testsuite=Feature --stop-on-failure

# Run PHPUnit tests using phpunit command
api-test-php-unit:
	$(DOCKER_COMPOSE) exec $(API_CONTAINER) php /var/www/html/artisan phpunit

# Generate Swagger documentation
api-build-swagger:
	$(DOCKER_COMPOSE) exec $(API_CONTAINER) php /var/www/html/artisan l5-swagger:generate

# Generate Passport keys
api-passport-key:
	$(DOCKER_COMPOSE) exec $(API_CONTAINER) php /var/www/html/artisan passport:keys --force

# Create a Passport client and save the output to a file
api-passport-generate:
	$(DOCKER_COMPOSE) exec $(API_CONTAINER) php /var/www/html/artisan passport:client --password --name='Laravel Password Grant Client' --provider=users > .passport
	$(DOCKER_COMPOSE) exec $(API_CONTAINER) cat .passport

# Fix permissions for storage and framework directories
fix-permissions:
	$(DOCKER_COMPOSE) exec $(API_CONTAINER) bash -c 'chmod -R 755 /var/www/html/storage/logs && chmod -R 755 /var/www/html/storage/framework && find /var/www/html/storage -type f -exec chmod 644 {} \;'
