#!/bin/bash

# Create required directories
mkdir -p docker/php docker/nginx

# Copy the configuration files
echo "Copying configuration files..."
cp docker-compose.yml ./
cp docker/php/Dockerfile docker/php/
cp docker/php/php.ini docker/php/
cp docker/nginx/default.conf docker/nginx/

# Make sure script is executable
chmod +x setup-docker.sh

# Start Docker Compose
echo "Starting Docker Compose..."
docker compose up -d

# Wait for services to be ready
echo "Waiting for services to be ready..."
sleep 10

# Install Symfony dependencies
echo "Installing Symfony dependencies..."
docker compose exec php composer install

# Update .env file with database configuration
echo "Updating .env file..."
sed -i 's#DATABASE_URL=.*#DATABASE_URL="postgresql://hotel_user:hotel_password@postgres:5432/hotel_db"#g' .env

# Create database tables
echo "Creating database tables..."
docker compose exec php bin/console doctrine:schema:update --force

echo "Setup completed! Your Symfony application is ready at http://localhost:8080"
