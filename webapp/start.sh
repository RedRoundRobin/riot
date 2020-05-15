#!/bin/sh

echo "Web application starting..."

if [ ! -d "vendor/" ]; then
	composer install --optimize-autoloader --no-dev
fi

if [ ! -d "node_modules/" ]; then
	npm install --production
fi

npm run prod

php artisan view:cache

php artisan serve --host=0.0.0.0 --port=8000 --env=prod
