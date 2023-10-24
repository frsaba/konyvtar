# Instructions
## Install dependencies
PHP 8.2.11 - enable extensions `fileinfo`, `pdo_mysql`
```
composer install
yarn install
```
## Configure .env
Create a .env file based on .env.example and edit the database credentials
```
php artisan key:generate
```
## Make database migrations
```
php artisan migrate
```
## Run
```
yarn dev
php artisan serve
```