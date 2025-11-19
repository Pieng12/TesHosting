<?php

// Test script untuk leaderboard
require_once 'vendor/autoload.php';

use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing leaderboard query...\n";
    
    $users = User::where('user_type', '!=', 'customer')
                ->where('completed_jobs', '>', 0)
                ->orderBy('rating', 'desc')
                ->orderBy('completed_jobs', 'desc')
                ->limit(20)
                ->get();
    
    echo "Found " . $users->count() . " users\n";
    
    $data = $users->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profile_image' => $user->profile_image,
            'user_type' => $user->user_type,
            'rating' => $user->rating,
            'completed_jobs' => $user->completed_jobs,
            'total_earnings' => $user->total_earnings,
            'is_verified' => $user->is_verified,
            'current_address' => $user->current_address,
        ];
    });
    
    echo "Data mapped successfully\n";
    echo "First user: " . json_encode($data->first()) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}





