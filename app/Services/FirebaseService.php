<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;

class FirebaseService
{
    protected Messaging $messaging;

    public function __construct()
    {
        $credentialsPath = storage_path('app/firebase/firebase-key.json');

        if (!file_exists($credentialsPath)) {
            if (!env('FIREBASE_JSON')) {
                throw new \Exception("Firebase JSON not set in environment variables.");
            }
            file_put_contents($credentialsPath, env('FIREBASE_JSON'));
        }

        $factory = (new Factory)
            ->withServiceAccount($credentialsPath);

        $this->messaging = $factory->createMessaging();
    }

    public function getMessaging(): Messaging
    {
        return $this->messaging;
    }
}
