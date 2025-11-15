<?php

// Test script untuk memverifikasi API Laravel
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\User;

// Test koneksi database
try {
    $users = DB::table('users')->count();
    echo "✅ Database connection successful! Users count: $users\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Test API endpoints
$baseUrl = 'http://localhost:8000/api';

// Test register endpoint
echo "\n🧪 Testing Register API...\n";
$registerData = [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => 'password123',
    'phone' => '081234567890',
    'user_type' => 'customer'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/register');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($registerData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 201) {
    echo "✅ Register API working!\n";
    $responseData = json_decode($response, true);
    if (isset($responseData['data']['token'])) {
        echo "✅ Token generated: " . substr($responseData['data']['token'], 0, 20) . "...\n";
    }
} else {
    echo "❌ Register API failed! HTTP Code: $httpCode\n";
    echo "Response: $response\n";
}

// Test login endpoint
echo "\n🧪 Testing Login API...\n";
$loginData = [
    'email' => 'test@example.com',
    'password' => 'password123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ Login API working!\n";
    $responseData = json_decode($response, true);
    if (isset($responseData['data']['token'])) {
        $token = $responseData['data']['token'];
        echo "✅ Login successful! Token: " . substr($token, 0, 20) . "...\n";
        
        // Test protected endpoint
        echo "\n🧪 Testing Protected User API...\n";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/user');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            echo "✅ Protected User API working!\n";
            $userData = json_decode($response, true);
            echo "✅ User data: " . $userData['data']['name'] . " (" . $userData['data']['email'] . ")\n";
        } else {
            echo "❌ Protected User API failed! HTTP Code: $httpCode\n";
        }
    }
} else {
    echo "❌ Login API failed! HTTP Code: $httpCode\n";
    echo "Response: $response\n";
}

echo "\n🎉 API testing completed!\n";





