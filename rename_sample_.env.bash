#!/usr/bin/env bash

echo docker-compose .env
cp -i ./docker/.env.sample ./docker/.env
echo php app.env
cp -i ./docker/php/app.env.sample ./docker/php/app.env
echo mysql .env
cp -i ./docker/mysql/.env.sample ./docker/mysql/.env
