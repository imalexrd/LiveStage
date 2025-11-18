# Environment Initialization Guide

This document provides the complete sequence of commands required to set up the development and testing environment for this project from a clean state.

## 1. System Dependencies

First, install the necessary system-level packages, including PHP, Composer, and PostgreSQL.

```bash
sudo apt-get update
sudo apt-get install -y php8.3 php8.3-pgsql php8.3-zip php8.3-xml php8.3-curl php8.3-mbstring php8.3-dom
sudo apt-get install -y composer
sudo apt-get install -y postgresql postgresql-contrib
```

## 2. Database Setup

Once PostgreSQL is installed, start the service and create the application and test databases, along with a user.

```bash
# Start the PostgreSQL service
sudo service postgresql start

# Create the main application database
sudo -u postgres psql -c "CREATE DATABASE laravel;"

# Create the test database
sudo -u postgres psql -c "CREATE DATABASE laravel_test;"

# Create the application user (if it doesn't already exist)
# Note: This command may fail if the user already exists, which is safe to ignore.
sudo -u postgres psql -c "CREATE USER root WITH PASSWORD 'password';"

# Grant privileges for the main database
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE laravel TO root;"
sudo -u postgres psql -c "GRANT ALL ON SCHEMA public TO root;" -d laravel

# Grant privileges for the test database
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE laravel_test TO root;"
sudo -u postgres psql -c "GRANT ALL ON SCHEMA public TO root;" -d laravel_test
```

## 3. Application Setup

With the environment prepared, set up the Laravel application.

```bash
# Install PHP dependencies
composer install

# Create the main environment file (customize as needed)
cp .env.example .env

# Generate the application key for the main .env file
php artisan key:generate

# Create the testing environment file
# Note: The APP_KEY must be a valid base64-encoded 32-byte string.
echo "APP_ENV=testing" > .env.testing
echo "APP_KEY=base64:$(openssl rand -base64 32)" >> .env.testing
echo "DB_CONNECTION=pgsql" >> .env.testing
echo "DB_HOST=127.0.0.1" >> .env.testing
echo "DB_PORT=5432" >> .env.testing
echo "DB_DATABASE=laravel_test" >> .env.testing
echo "DB_USERNAME=root" >> .env.testing
echo "DB_PASSWORD=password" >> .env.testing
echo "CACHE_DRIVER=array" >> .env.testing
echo "QUEUE_CONNECTION=sync" >> .env.testing
echo "SESSION_DRIVER=array" >> .env.testing

# Run database migrations and seeders for the main database
php artisan migrate:fresh --seed
```

## 4. Running Tests

To verify that the setup is correct, run the test suite.

```bash
./vendor/bin/phpunit
```
