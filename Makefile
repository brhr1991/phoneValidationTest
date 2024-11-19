init: docker-down-clear docker-pull docker-build docker-up composer-install

up: docker-up
down: docker-down
restart: down up

docker-up:
	docker compose up -d
docker-down:
	docker compose down
docker-pull:
	docker compose pull
docker-build:
	docker compose build --pull
docker-down-clear:
	docker compose down -v --remove-orphans
composer-install:
	docker compose run --rm backend-php-cli composer install
test:
	docker compose run --rm backend-php-cli ./vendor/bin/phpunit