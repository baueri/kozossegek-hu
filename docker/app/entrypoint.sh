#!/bin/bash

until nc -z -v -w30 mysql 3306
do
  echo "Waiting for database connection..."
  sleep 1
done

mkdir -p ./cache && mkdir -p ${STORAGE_PATH}
chown 1000:1000 -R /app/cache && chown 1000:1000 -R ${STORAGE_PATH}
chmod -R +w ${STORAGE_PATH} && chmod -R 777 ./cache/
ln -s ${STORAGE_PATH}/public/ /app/public/storage

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