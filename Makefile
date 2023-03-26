SHELL=/bin/bash
WEB_CONTAINER=nginx
APP_CONTAINER=php
DB_CONTAINER=mysql

init-local:
	cp ./app/.env{.example,}
	@make _init_common
init:
	<./app/.env.example sed -E 's/(DB_PASSWORD=)/\1root/' > ./app/.env
	<./app/.env.example sed -E 's/(LOG_LEVEL=)/\1error/' > ./app/.env
	@make _init_common
_init_common:
	@make down
	@make build-no-chace
	@make up
	cp ./docker/mysql/.env{.sample,}
	docker exec $(APP_CONTAINER) bash -c 'php artisan key:generate'
	docker exec $(APP_CONTAINER) bash -c 'php artisan storage:link'
	docker exec $(APP_CONTAINER) bash -c 'sudo chmod -R 777 storage bootstrap/cache'
	docker exec $(APP_CONTAINER) bash -c 'find . -name ".gitignore" | xargs sudo chmod 644'
	sleep 30
	@make seed-fresh
# ---------------
# laravel
# ---------------
seed-fresh:
	docker exec $(APP_CONTAINER) bash -c 'php artisan migrate:fresh --seed'
seed-article100:
	docker exec $(APP_CONTAINER) bash -c 'php artisan tinker --execute '\''\App\Models\Article::factory(100)->create();'\'''
rollback-test:
	docker exec $(APP_CONTAINER) bash -c 'php artisan migrate:fresh'
	docker exec $(APP_CONTAINER) bash -c 'php artisan migrate:refresh'
tinker:
	docker exec -it $(APP_CONTAINER) bash -c 'php artisan tinker'
test:
	docker exec $(APP_CONTAINER) bash -c 'php artisan test'
npm-watch:
	docker exec $(APP_CONTAINER) bash -c 'npm run watch'
log:
	docker exec $(APP_CONTAINER) bash -c '</var/www/app/storage/logs/laravel.log tail -10000'
# ---------------
# wev server
# ---------------
reload-nginx:
	docker exec $(WEB_CONTANER) bash -c 'nginx -s reload'
# ---------------
# database
# ---------------
db:
	@make mysql
mysql:
	docker exec -it $(DB_CONTAINER) bash -c 'mysql -u $$MYSQL_USER -p$$MYSQL_PASSWORD $$MYSQL_DATABASE'
# ---------------
# docker
# ---------------
up:
	cd docker && docker-compose up -d
down:
	cd docker && docker-compose down --remove-orphans
build:
	cd docker && docker-compose build
build-no-chace:
	cd docker && docker-compose build --no-cache --force-rm
restart:
	cd docker && docker compose down && docker compose up -d
rmi-all:
	cd docker && docker compose down --rmi=all'
