SHELL = sh
.EXPORT_ALL_VARIABLES:
.ONESHELL:

DC=docker-compose -f docker-compose.yml
CONSOLE=${DC} exec maxima.admin.php
NODE=${DC} exec maxima.admin.node

.PHONY: reinstall-container build remove-with-volumes up down php-cli db-migrate db-migrate-down db-migrate-create db-dump-up help install composer-install key-generate cache-clear cache-config reverb-serve

default: help

restart-container: down up ## перезапустит контейнеры
reinstall-container: remove-with-volumes build ## удалит и пересоберет контейнеры

build: ## собрать контейнеры docker-compose.yml
	${DC} up --build --remove-orphans -d

remove-with-volumes: ## удалить контейнеры вместе с volumes (включая анонимные volumes) docker-compose.yml
	${DC} down --remove-orphans --volumes

down: ## остановить контейнеры docker-compose.yml
	${DC} stop

up: ## Запустить контейнеры
	${DC} up -d

php-cli: ## зайти в консоль php контейнера docker-compose.yml
	${DC} exec maxima.admin.php bash

db-migrate: ## Накатить миграции
	${CONSOLE} php artisan migrate --force

db-migrate-down: ## Откатить последнюю миграцию
	${CONSOLE} php artisan migrate:rollback

db-migrate-create: ## создать миграцию make db-migrate-create name=<name>
	${CONSOLE} php artisan make:migration $(name)

install: build composer-install key-generate cache-clear db-migrate ## Полная установка проекта

composer-install: ## Установка зависимостей Laravel
	${CONSOLE} composer install

key-generate: ## Генерация ключа приложения
	${CONSOLE} php artisan key:generate

cache-clear: ## Очистка всех кэшей
	${CONSOLE} php artisan cache:clear
	${CONSOLE} php artisan config:clear
	${CONSOLE} php artisan view:clear
	${CONSOLE} php artisan route:clear

cache-config: ## Кэширование конфигураций
	${CONSOLE} php artisan config:cache
	${CONSOLE} php artisan route:cache
	${CONSOLE} php artisan view:cache

reverb-serve: ## Запуск WebSocket сервера
	${CONSOLE} php artisan reverb:start

help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[\a-zA-Z0-9_-]+:.*?## / {printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build-js: ## Собрать JS приложение
	${NODE} npm run build

dev-js: ## Собрать JS в режиме разработки
	${NODE} npm run dev

npm-install: ## Установить npm пакеты
	${NODE} npm install
