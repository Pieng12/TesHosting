<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Create a notification for a single user
     */
    public static function createNotification(
        int $userId,
        string $type,
        string $title,
        string $body,
        ?string $relatedType = null,
        ?int $relatedId = null,
        ?array $data = null
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'is_read' => false,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
            'data' => $data,
        ]);
    }

    /**
     * Create notifications for multiple users
     */
    public static function createNotificationsForUsers(
        array $userIds,
        string $type,
        string $title,
        string $body,
        ?string $relatedType = null,
        ?int $relatedId = null,
        ?array $data = null
    ): void {
        $notifications = [];
        $now = now();

        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'is_read' => false,
                'related_type' => $relatedType,
                'related_id' => $relatedId,
                'data' => json_encode($data),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($notifications)) {
            Notification::insert($notifications);
        }
    }

    /**
     * Create notification for nearby users (for SOS)
     */
    public static function createSOSNotificationForNearbyUsers(
        float $latitude,
        float $longitude,
        float $radius,
        int $sosId,
        string $sosTitle,
        int $excludeUserId
    ): void {
        // Get nearby users within radius (excluding the SOS requester)
        // Note: User model uses current_latitude and current_longitude
        $nearbyUsers = User::selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians(current_latitude)) * cos(radians(current_longitude) - radians(?)) + sin(radians(?)) * sin(radians(current_latitude)))) AS distance", [$latitude, $longitude, $latitude])
            ->where('id', '!=', $excludeUserId)
            ->whereNotNull('current_latitude')
            ->whereNotNull('current_longitude')
            ->having('distance', '<=', $radius)
            ->get();

        if ($nearbyUsers->isEmpty()) {
            return;
        }

        $notifications = [];
        $now = now();

        foreach ($nearbyUsers as $user) {
            $distance = round($user->distance, 1);
            $notifications[] = [
                'user_id' => $user->id,
                'type' => 'sos_nearby',
                'title' => '🚨 SOS Darurat di Sekitar',
                'body' => "Ada sinyal darurat \"{$sosTitle}\" sekitar {$distance} km dari lokasi Anda",
                'is_read' => false,
                'related_type' => 'sos',
                'related_id' => $sosId,
                'data' => json_encode([
                    'sos_id' => $sosId,
                    'distance' => $distance,
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($notifications)) {
            Notification::insert($notifications);
        }
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead(int $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if (!$notification) {
            return false;
        }

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return true;
    }

    /**
     * Mark all notifications as read for a user
     */
    public static function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
}

