FROM php:7.1-cli-alpine

ARG uid
ARG gid

# install git and bash
RUN apk update && \
    apk upgrade && \
    apk add git bash

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

RUN addgroup -g ${gid} devel && \
    adduser -D -G devel -u ${uid} devel

WORKDIR /home/devel

USER devel:devel
