#!/bin/bash

until nc -z -v -w30 mysql 3306
do
  echo "Waiting for database connection..."
  sleep 1
done

mkdir -p ./cache && mkdir -p ${STORAGE_PATH}
chown www-data:www-data -R /app/cache && chown www-data:www-data -R ${STORAGE_PATH}

service apache2 start

composer install
composer migrate

echo "
               &
              &&&
               &
         &&&   &   &&&
      &&     &&&&&     &&
    &&     &&&   &&&     &
    &   &&&         &&&   &
   %& &&     (( ((    &&& &&
    &% (    ((( (((    ( &&
 && ((      ((( (((      (( &%
   (  ((               ((  (
        (((         (((
           (((((((((
"

while true
do
   sleep 1
done