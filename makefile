install: api-env api-composer-install api-build up sleep api-db api-key

sleep:
	sleep 3

ps:
	docker compose ps

up:
	docker compose up -d

up-recreate:
	docker compose up -d --force-recreate

down:
	docker compose down

forget:
	docker compose down --rmi all --volumes
	docker volume rm backend-test_sail-mysql 2>/dev/null

db-shell:
	mysql -h 127.0.0.1 -P 3306 -u sail -ppassword

api-build:
	USER_ID=$(shell id -u) GROUP_ID=$(shell id -g) docker compose build --no-cache

api-db:
	docker compose exec api php /var/www/html/artisan migrate:fresh
	docker compose exec api php /var/www/html/artisan db:seed

api-key:
	docker compose exec api php /var/www/html/artisan key:generate

api-env:
	cp .env.example .env

api-config-cache:
	docker compose exec api php /var/www/html/artisan config:cache

api-composer-install:
	docker compose exec api composer install

api-shell:
	docker compose exec -it api bash -c 'su sail'

api-root-shell:
	docker compose exec -it api bash

api-test:
	docker compose exec api php /var/www/html/artisan test

api-test-feature:
	docker compose exec api php /var/www/html/artisan test --testsuite=Feature --stop-on-failure

api-test-php-unit:
	docker compose exec api php /var/www/html/artisan phpunit

api-build-swagger:
	docker compose exec api php /var/www/html/artisan l5-swagger:generate


fix-permissions:
	docker compose exec api bash -c 'chmod -R 777 /var/www/html/storage/logs && chmod -R 777 /var/www/html/storage/framework'
