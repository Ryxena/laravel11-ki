#!/bin/bash
php artisan ide-helper:generate
php artisan ide-helper:models -N
php artisan ide-helper:meta
php artisan optimize:clear
php artisan ser
