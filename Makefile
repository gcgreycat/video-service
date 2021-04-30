include .env

build-toolkit:
	docker build -t ${docker_prefix}/toolkit:latest --build-arg uid=${uid} --build-arg gid=${gid} ./docker/toolkit
run-dev:
	docker-compose -p ${compose_project_name} -f ./docker/dev/docker-compose.yml up --build
composer-install:
	./tools/composer.sh install
seed-db:
	docker-compose -p ${compose_project_name} -f ./docker/dev/docker-compose.yml exec php-fpm php ./artisan migrate:fresh --seed
