@echo off
echo Setting up Laravel Backend for Flutter App...
echo.

echo 1. Installing dependencies...
composer install
echo.

echo 2. Creating .env file...
if not exist .env (
    copy .env.example .env
    echo .env file created from .env.example
) else (
    echo .env file already exists
)
echo.

echo 3. Generating application key...
php artisan key:generate
echo.

echo 4. Running migrations...
php artisan migrate
echo.

echo 5. Creating storage link...
php artisan storage:link
echo.

echo 6. Starting server...
echo Server will be available at: http://localhost:8000
echo API endpoints will be available at: http://localhost:8000/api
echo.
echo Press Ctrl+C to stop the server
php artisan serve





