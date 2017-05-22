#!/bin/bash

cd $(dirname "$0")

set -e errexit
set -o pipefail
set -a
. ".env"
set +a

docker exec -it ${COMPOSE_PROJECT_NAME}_webserver bash