# Minimal Makefile for Laravel + Docker

up:
	docker compose up

down:
	docker compose down

shell:
	docker compose exec php-api bash

test:
	docker compose exec php-api php artisan test

migrate:
	docker compose exec php-api php artisan migrate

seed:
	docker compose exec php-api php artisan db:seed

logs:
	docker compose logs -f --tail=100
