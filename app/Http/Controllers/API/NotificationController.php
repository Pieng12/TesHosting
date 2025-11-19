<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get user notifications
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 20);
        $offset = $request->get('offset', 0);

        // Get notifications from database
        $query = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        $total = $query->count();
        $unreadCount = $query->where('is_read', false)->count();

        $notifications = $query->offset($offset)
            ->limit($limit)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'body' => $notification->body,
                    'is_read' => (bool) $notification->is_read,
                    'related_type' => $notification->related_type,
                    'related_id' => $notification->related_id,
                    'data' => $notification->data,
                    'created_at' => $notification->created_at->toISOString(),
                ];
            })
            ->values(); // Reset array keys to ensure it's a proper array, not object

        // Return format that matches frontend expectations
        // Frontend expects: response['data'] to be either a List or Map with 'data' key
        return response()->json([
            'success' => true,
            'data' => $notifications, // Return notifications directly as array
            'message' => 'Notifications retrieved successfully'
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'id' => 'required|integer|exists:notifications,id'
        ]);
        
        $success = NotificationService::markAsRead($request->id, $user->id);
        
        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found or unauthorized'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        
        $count = NotificationService::markAllAsRead($user->id);
        
        return response()->json([
            'success' => true,
            'message' => "All notifications marked as read ({$count} notifications)",
            'count' => $count
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }
        
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
    }
}





