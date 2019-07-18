#!/usr/bin/env bash

INSTALL=${1-false}

cd ..

docker-compose -f docker-compose.phpunit.yml up -d

if [[ ${INSTALL} = true ]]; then
	docker-compose -f docker-compose.phpunit.yml run --rm wordpress_phpunit bin/install-wp-tests.sh wordpress_test root root mysql_test latest true
fi

docker-compose -f docker-compose.phpunit.yml run --rm wordpress_phpunit vendor/bin/phpunit
docker-compose down --remove-orphans

read
