#!/bin/bash

echo "Running Private Order Seeder..."
echo

cd "$(dirname "$0")"

echo "Step 1: Running migrations..."
php artisan migrate:fresh

echo
echo "Step 2: Running seeders..."
php artisan db:seed

echo
echo "Step 3: Private Order Seeder completed!"
echo
echo "You can now test the private order features:"
echo "- Login as different users"
echo "- Check leaderboard for workers"
echo "- Create private orders"
echo "- Verify private orders don't appear in All Orders"
echo "- Check My Orders for private order distinction"
echo










