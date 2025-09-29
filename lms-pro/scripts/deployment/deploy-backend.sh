#!/bin/bash

# This is a sample script for deploying the backend application.

echo "Starting backend deployment..."

# 1. Pull the latest changes from the git repository
git pull origin main

# 2. Install/update composer dependencies
docker-compose exec backend composer install --no-dev --optimize-autoloader

# 3. Run database migrations
docker-compose exec backend php artisan migrate --force

# 4. Clear caches
docker-compose exec backend php artisan cache:clear
docker-compose exec backend php artisan config:cache
docker-compose exec backend php artisan route:cache
docker-compose exec backend php artisan view:cache

# 5. Restart the application server
docker-compose restart backend

echo "Backend deployment finished."