#!/bin/bash

# This script is for setting up the development environment.

echo "Starting environment setup..."

# 1. Copy .env files
echo "Setting up environment variables..."
cp -n .env.example .env
cp -n backend/.env.example backend/.env
cp -n frontend-web/.env.example frontend-web/.env
cp -n mobile-app/.env.example mobile-app/.env

# 2. Build and run docker containers
echo "Building and starting Docker containers..."
docker-compose up --build -d

# 3. Install backend dependencies
echo "Installing backend dependencies..."
docker-compose exec backend composer install

# 4. Generate Laravel application key
echo "Generating application key..."
docker-compose exec backend php artisan key:generate

# 5. Run database migrations and seeders
echo "Running database migrations and seeding data..."
docker-compose exec backend php artisan migrate --seed

# 6. Install frontend dependencies
echo "Installing frontend dependencies..."
docker-compose exec frontend npm install

echo "Setup complete! The application should be running."
echo "Web app: http://localhost:3000"
echo "API: http://localhost:8000/api/v1"