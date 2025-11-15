<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\User;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users - all can create and take jobs
        $users = User::all();

        $jobs = [
            [
                'customer_id' => $users->random()->id,
                'title' => 'House Cleaning Service',
                'description' => 'Need someone to clean my house thoroughly. 3 bedrooms, 2 bathrooms, living room and kitchen. Must be experienced and bring own cleaning supplies.',
                'category' => 'cleaning',
                'price' => 150000,
                'latitude' => -6.200000,
                'longitude' => 106.816666,
                'address' => 'Jl. Sudirman No. 123, Jakarta Selatan',
                'scheduled_time' => now()->addDays(2)->setTime(9, 0),
                'status' => 'pending',
                'image_urls' => json_encode(['https://example.com/cleaning1.jpg', 'https://example.com/cleaning2.jpg']),
                'additional_info' => json_encode(['rooms' => 3, 'bathrooms' => 2, 'supplies_needed' => true]),
            ],
            [
                'customer_id' => $users->random()->id,
                'title' => 'AC Maintenance',
                'description' => 'My AC is not cooling properly. Need someone to check and repair. Unit is 2 years old, still under warranty.',
                'category' => 'maintenance',
                'price' => 200000,
                'latitude' => -6.201000,
                'longitude' => 106.817000,
                'address' => 'Jl. Thamrin No. 456, Jakarta Pusat',
                'scheduled_time' => now()->addDays(1)->setTime(14, 0),
                'status' => 'pending',
                'image_urls' => json_encode(['https://example.com/ac1.jpg']),
                'additional_info' => json_encode(['warranty' => true, 'age' => '2 years']),
            ],
            [
                'customer_id' => $users->random()->id,
                'title' => 'Food Delivery',
                'description' => 'Need someone to pick up food from restaurant and deliver to my office. Distance is about 5km.',
                'category' => 'delivery',
                'price' => 50000,
                'latitude' => -6.202000,
                'longitude' => 106.818000,
                'address' => 'Jl. Gatot Subroto No. 789, Jakarta Utara',
                'scheduled_time' => now()->addHours(2),
                'status' => 'pending',
                'image_urls' => json_encode([]),
                'additional_info' => json_encode(['distance' => '5km', 'restaurant' => 'Warung Makan Sederhana']),
            ],
            [
                'customer_id' => $users->random()->id,
                'title' => 'Math Tutoring',
                'description' => 'Need a tutor for my 15-year-old son. Subject: Algebra and Geometry. Prefer someone with teaching experience.',
                'category' => 'tutoring',
                'price' => 100000,
                'latitude' => -6.203000,
                'longitude' => 106.819000,
                'address' => 'Jl. Kemang Raya No. 321, Jakarta Barat',
                'scheduled_time' => now()->addDays(3)->setTime(16, 0),
                'status' => 'pending',
                'image_urls' => json_encode([]),
                'additional_info' => json_encode(['subject' => 'Math', 'student_age' => 15, 'experience_required' => true]),
            ],
            [
                'customer_id' => $users->random()->id,
                'title' => 'Event Photography',
                'description' => 'Need a photographer for my wedding ceremony. Event will be held at a hotel. Need professional equipment and portfolio.',
                'category' => 'photography',
                'price' => 2500000,
                'latitude' => -6.204000,
                'longitude' => 106.820000,
                'address' => 'Hotel Mulia, Jakarta Timur',
                'scheduled_time' => now()->addWeeks(2)->setTime(10, 0),
                'status' => 'pending',
                'image_urls' => json_encode(['https://example.com/wedding1.jpg']),
                'additional_info' => json_encode(['event_type' => 'wedding', 'equipment_required' => true]),
            ],
            [
                'customer_id' => $users->random()->id,
                'title' => 'Cooking Service',
                'description' => 'Need someone to cook for a small party (10 people). Menu: Indonesian food. Must be hygienic and experienced.',
                'category' => 'cooking',
                'price' => 300000,
                'latitude' => -6.205000,
                'longitude' => 106.821000,
                'address' => 'Jl. Pondok Indah No. 654, Depok',
                'scheduled_time' => now()->addDays(5)->setTime(18, 0),
                'status' => 'pending',
                'image_urls' => json_encode([]),
                'additional_info' => json_encode(['guests' => 10, 'cuisine' => 'Indonesian', 'hygiene_important' => true]),
            ],
            [
                'customer_id' => $users->random()->id,
                'title' => 'Garden Maintenance',
                'description' => 'Need someone to maintain my garden. Tasks include watering, pruning, and fertilizing plants.',
                'category' => 'gardening',
                'price' => 120000,
                'latitude' => -6.206000,
                'longitude' => 106.822000,
                'address' => 'Jl. Bintaro Raya No. 987, Tangerang',
                'scheduled_time' => now()->addDays(4)->setTime(8, 0),
                'status' => 'pending',
                'image_urls' => json_encode(['https://example.com/garden1.jpg', 'https://example.com/garden2.jpg']),
                'additional_info' => json_encode(['tasks' => ['watering', 'pruning', 'fertilizing']]),
            ],
            [
                'customer_id' => $users->random()->id,
                'title' => 'Pet Care Service',
                'description' => 'Need someone to take care of my dog while I\'m away for 3 days. Dog is friendly and well-trained.',
                'category' => 'petCare',
                'price' => 400000,
                'latitude' => -6.207000,
                'longitude' => 106.823000,
                'address' => 'Jl. Bekasi Raya No. 147, Bekasi',
                'scheduled_time' => now()->addDays(6)->setTime(9, 0),
                'status' => 'pending',
                'image_urls' => json_encode(['https://example.com/dog1.jpg']),
                'additional_info' => json_encode(['pet_type' => 'dog', 'duration' => '3 days', 'well_trained' => true]),
            ],
            [
                'customer_id' => $users->random()->id,
                'title' => 'Computer Repair',
                'description' => 'My laptop is running slow and has some software issues. Need someone to diagnose and fix the problems.',
                'category' => 'maintenance',
                'price' => 180000,
                'latitude' => -6.208000,
                'longitude' => 106.824000,
                'address' => 'Jl. Senayan No. 258, Jakarta Selatan',
                'scheduled_time' => now()->addDays(3)->setTime(13, 0),
                'status' => 'pending',
                'image_urls' => json_encode([]),
                'additional_info' => json_encode(['device_type' => 'laptop', 'issue' => 'slow performance']),
            ],
            [
                'customer_id' => $users->random()->id,
                'title' => 'Moving Assistance',
                'description' => 'Need help moving furniture from my old apartment to new one. Distance is about 10km. Need strong people.',
                'category' => 'other',
                'price' => 500000,
                'latitude' => -6.209000,
                'longitude' => 106.825000,
                'address' => 'Jl. Kuningan No. 369, Jakarta Selatan',
                'scheduled_time' => now()->addDays(7)->setTime(10, 0),
                'status' => 'pending',
                'image_urls' => json_encode(['https://example.com/moving1.jpg']),
                'additional_info' => json_encode(['distance' => '10km', 'furniture' => true, 'strength_required' => true]),
            ],
        ];

        foreach ($jobs as $jobData) {
            Job::create($jobData);
        }

        // Keep all jobs in pending status for applications
        // Jobs will be assigned through the application process
    }
}





