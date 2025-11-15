<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample users
        $users = [
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                // Removed user_type - all users can create and take jobs
                'rating' => 4.8,
                'completed_jobs' => 15,
                'total_earnings' => 2500000,
                'is_verified' => true,
                'current_latitude' => -6.200000,
                'current_longitude' => 106.816666,
                'current_address' => 'Jakarta Selatan, Indonesia',
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                // Removed user_type - all users can create and take jobs
                'rating' => 4.9,
                'completed_jobs' => 45,
                'total_earnings' => 8500000,
                'is_verified' => true,
                'current_latitude' => -6.201000,
                'current_longitude' => 106.817000,
                'current_address' => 'Jakarta Pusat, Indonesia',
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                // Removed user_type - all users can create and take jobs
                'rating' => 4.7,
                'completed_jobs' => 32,
                'total_earnings' => 5200000,
                'is_verified' => true,
                'current_latitude' => -6.202000,
                'current_longitude' => 106.818000,
                'current_address' => 'Jakarta Utara, Indonesia',
            ],
            [
                'name' => 'Lisa Brown',
                'email' => 'lisa@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567893',
                // Removed user_type - all users can create and take jobs
                'rating' => 4.5,
                'completed_jobs' => 8,
                'total_earnings' => 1200000,
                'is_verified' => false,
                'current_latitude' => -6.203000,
                'current_longitude' => 106.819000,
                'current_address' => 'Jakarta Barat, Indonesia',
            ],
            [
                'name' => 'David Lee',
                'email' => 'david@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567894',
                // Removed user_type - all users can create and take jobs
                'rating' => 4.6,
                'completed_jobs' => 28,
                'total_earnings' => 4200000,
                'is_verified' => true,
                'current_latitude' => -6.204000,
                'current_longitude' => 106.820000,
                'current_address' => 'Jakarta Timur, Indonesia',
            ],
            [
                'name' => 'Emma Davis',
                'email' => 'emma@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567895',
                // Removed user_type - all users can create and take jobs
                'rating' => 4.9,
                'completed_jobs' => 12,
                'total_earnings' => 1800000,
                'is_verified' => true,
                'current_latitude' => -6.205000,
                'current_longitude' => 106.821000,
                'current_address' => 'Depok, Indonesia',
            ],
            [
                'name' => 'Alex Chen',
                'email' => 'alex@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567896',
                // Removed user_type - all users can create and take jobs
                'rating' => 4.8,
                'completed_jobs' => 38,
                'total_earnings' => 6800000,
                'is_verified' => true,
                'current_latitude' => -6.206000,
                'current_longitude' => 106.822000,
                'current_address' => 'Tangerang, Indonesia',
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567897',
                // Removed user_type - all users can create and take jobs
                'rating' => 4.7,
                'completed_jobs' => 25,
                'total_earnings' => 3800000,
                'is_verified' => true,
                'current_latitude' => -6.207000,
                'current_longitude' => 106.823000,
                'current_address' => 'Bekasi, Indonesia',
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}





