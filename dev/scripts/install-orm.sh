#!/bin/bash

# Install Doctrine ORM Pack
docker compose exec php composer require symfony/orm-pack

# Install maker bundle for entity creation
docker compose exec php composer require --dev symfony/maker-bundle

# Update the .env file (fix the previous command)
echo "Updating .env file with database configuration..."
docker compose exec php sh -c 'sed -i "s#DATABASE_URL=.*#DATABASE_URL=\"postgresql://hotel_user:hotel_password@postgres:5432/hotel_db?serverVersion=16&charset=utf8\"#g" .env'

# Create the database
docker compose exec php bin/console doctrine:database:create --if-not-exists

echo "ORM setup completed!"
