#!/bin/bash

if [[ $1 =~ ^(-e) ]]
then
    email="$2"
fi

cp .env.example .env

docker compose up -d --build

docker exec kozossegek_app php console install --name=Admin --username=admin --email="$email" --password=pw --seed