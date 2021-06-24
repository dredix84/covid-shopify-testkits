#!/bin/bash

echo "Resetting code to HEAD and getting latest copy"
git reset --hard HEAD
git pull

echo "Install Project JS Dependencies"
npm install
npm run prod

echo "Installing Project PHP Dependencies"
composer install --no-interaction

echo "Migrating Database"
php artisan migrate --force

echo "Linking Storage"
php artisan storage:link

echo "Clearing Application Cache"
php artisan optimize:clear
php artisan telescope:publish

chmod +x deploy.sh

echo "All Done!!!"
