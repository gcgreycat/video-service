#!/bin/bash

script=$(readlink -f "$0")
script_path=$(dirname "$script")

"$script_path"/toolkit.sh composer "$@"
