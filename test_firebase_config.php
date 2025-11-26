<?php
// Test Firebase credentials

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Firebase Configuration Test ===\n\n";

// 1. Check credentials path
$credPath = config('services.firebase.credentials_path');
echo "1. Credentials Path: " . $credPath . "\n";
echo "   Exists: " . (file_exists($credPath) ? "✅ YES" : "❌ NO") . "\n\n";

// 2. Check FCM Server Key
$fcmKey = config('services.firebase.fcm_server_key');
echo "2. FCM Server Key: " . ($fcmKey ? "✅ SET (length: " . strlen($fcmKey) . ")" : "❌ NOT SET") . "\n\n";

// 3. Try load Firebase credentials
try {
    $credentials = json_decode(file_get_contents($credPath), true);
    echo "3. Firebase Credentials:\n";
    echo "   Project ID: " . ($credentials['project_id'] ?? 'N/A') . "\n";
    echo "   Type: " . ($credentials['type'] ?? 'N/A') . "\n";
    echo "   Service Account Email: " . ($credentials['client_email'] ?? 'N/A') . "\n";
    echo "   ✅ Credentials loaded successfully\n\n";
} catch (\Exception $e) {
    echo "3. ❌ Error loading credentials: " . $e->getMessage() . "\n\n";
}

// 4. Summary
echo "=== Summary ===\n";
if (file_exists($credPath) && $fcmKey) {
    echo "✅ Firebase is properly configured!\n";
    echo "✅ Ready to send push notifications\n";
} else {
    echo "⚠️  Some configuration is missing\n";
    if (!file_exists($credPath)) echo "   - Credentials file not found\n";
    if (!$fcmKey) echo "   - FCM_SERVER_KEY not set in .env\n";
}
