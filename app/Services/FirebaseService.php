<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\Messaging;

class FirebaseService
{
    protected Database $database;
    protected Messaging $messaging;

    public function __construct()
    {
        // Path sementara untuk JSON di storage
        $credentialsPath = storage_path('app/firebase/firebase-key.json');

        // Jika file belum ada, buat dari env
        if (!file_exists($credentialsPath)) {
            if (!env('FIREBASE_JSON')) {
                throw new \Exception("Firebase JSON not set in environment variables.");
            }
            file_put_contents($credentialsPath, env('FIREBASE_JSON'));
        }

        $factory = (new Factory)
            ->withServiceAccount($credentialsPath)
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL', 'https://laravelfirebasedemo-417d7-default-rtdb.firebaseio.com'));

        $this->database = $factory->createDatabase();
        $this->messaging = $factory->createMessaging();
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function getMessaging(): Messaging
    {
        return $this->messaging;
    }
}
