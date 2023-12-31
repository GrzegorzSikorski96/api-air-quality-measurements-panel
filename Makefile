bash:
	docker-compose exec air-quality-measurements-panel-app bash

up:
	docker-compose up -d

build:
	docker-compose build

pull:
	docker-compose pull

down:
	docker-compose down -v

sleep5:
	sleep 5

run: pull build up composer-install cache-clear create-database

cache-clear:
	-docker-compose exec air-quality-measurements-panel-app php bin/console cache:clear

composer-install:
	docker-compose exec air-quality-measurements-panel-app composer install

create-database:
	docker-compose exec air-quality-measurements-panel-app php bin/console doctrine:database:create --if-not-exists

migrate:
	docker-compose exec air-quality-measurements-panel-app php bin/console doctrine:migrations:migrate --no-interaction