#!/bin/bash
cp .env.example .env
composer require --dev barryvdh/laravel-ide-helper
composer require laravel/telescope --dev
composer install --prefer-dist --optimize-autoloader
composer update
php artisan key:generate
php artisan optimize:clear
php artisan telescope:instal
php artisan ide-helper:generate
php artisan ide-helper:models
php artisan ide-helper:meta
php artisan ide-helper:eloquent
php artisan migrate
php artisan ser
echo "Done"
