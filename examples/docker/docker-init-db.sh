#!/bin/bash

cd $(dirname "$0")

set -e errexit
set -o pipefail
set -a
. ".env"
set +a

docker exec -i ${COMPOSE_PROJECT_NAME}_db mysql -u root -p${MYSQL_ROOT_PASSWORD} ${MYSQL_DATABASE} < ../../resources/nestpay_payments.sql