#!/bin/bash

export $(grep -v '^#' .env | xargs -d '\n')

printenv uid
printenv gid

script=$(readlink -f "$0")
project_path=$(dirname "$script")

cache_folder="$project_path/../cache"
composer_cache_folder="$cache_folder/composer"

if [ ! -d "$cache_folder" ]; then
  mkdir -m 0777 "$cache_folder"
  chown -R "$uid":"$gid" "$cache_folder"
fi

if [ ! -d "$composer_cache_folder" ]; then
  mkdir -m 0777 "$composer_cache_folder"
  chown -R "$uid":"$gid" "$composer_cache_folder"
fi

docker run --rm -ti \
  -u "$uid:$gid" \
  -v "$project_path/../":/usr/src/app \
  -v "$composer_cache_folder":/.composer \
  -w /usr/src/app/application \
  "$docker_prefix/toolkit:latest" "$@"
