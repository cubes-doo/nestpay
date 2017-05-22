#!/bin/bash

cd $(dirname "$0")

set -e errexit
set -o pipefail
set -a
. ".env"
set +a

docker-compose stop
docker-compose rm
docker-compose up -d --force-recreate
