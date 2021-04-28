FROM php:7.1-fpm-alpine

RUN apk update && \
    apk upgrade && \
    docker-php-ext-install mysqli pdo_mysql
