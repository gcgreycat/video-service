include .env

build-toolkit:
	docker build -t ${docker_prefix}/toolkit:latest --build-arg uid=${uid} --build-arg gid=${gid} ./docker/toolkit
run-dev:
	docker-compose -f ./docker/dev/docker-compose.yml up
