#!/bin/bash

mkdir -p ./cache && mkdir -p ${STORAGE_PATH}
chown www-data:www-data -R /app/cache && chown www-data:www-data -R ${STORAGE_PATH}

service apache2 start

composer install
composer migrate
php install.php

while true
do
   sleep 1
done