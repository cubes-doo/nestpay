#!/bin/bash

SCRIPT_ARG=$1;

if [ -z "$SCRIPT_ARG" ]; then
	CONTAINER_ADDENDUM="webserver"
else
	CONTAINER_ADDENDUM=$SCRIPT_ARG
fi

cd $(dirname "$0")

set -e errexit
set -o pipefail
set -a
. ".env"
set +a

docker exec -it ${COMPOSE_PROJECT_NAME}_${CONTAINER_ADDENDUM} bash