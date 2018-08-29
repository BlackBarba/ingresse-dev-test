#!/bin/bash

docker-compose up -d

docker exec -it dev-test-application composer install
docker exec -it dev-test-application ./wait-for-it.sh mysql:3306 -t 0
docker exec -it dev-test-application php artisan migrate