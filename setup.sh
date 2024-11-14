#!/bin/bash
composer require --dev barryvdh/laravel-ide-helper
composer require laravel/telescope --dev
composer install --prefer-dist --optimize-autoloader
composer update
php artisan conf:clear
php artisan ide-helper:generate
php artisan ide-helper:models
php artisan ide-helper:meta
php artisan telescope:instal
php artisan migrate
php artisan ser
echo "Done"
