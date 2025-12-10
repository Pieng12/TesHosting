<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FcmTokenController extends Controller
{
    /**
     * Simpan / update FCM token untuk user yang sedang login.
     * Sekarang menyimpan ke device_tokens table dan mengikat ke device spesifik
     */
    public function update(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
            'device_id' => 'nullable|string', // Unique device identifier
            'device_name' => 'nullable|string',
            'platform' => 'nullable|in:ios,android,web',
            'app_version' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Use updateOrCreate to simplify logic and prevent race conditions.
        // This finds a token by its unique fcm_token.
        // If it exists, it updates it with the new user's info.
        // If it doesn't exist, it creates it.
        DeviceToken::updateOrCreate(
            ['fcm_token' => $request->fcm_token],
            [
                'user_id' => $user->id,
                'device_id' => $request->device_id,
                'device_name' => $request->device_name,
                'platform' => $request->platform,
                'app_version' => $request->app_version,
                'is_active' => true,
                'last_used_at' => now(),
            ]
        );

        // Deactivate any other tokens that might be associated with this user's other devices
        // to ensure only the most recent one is active, preventing duplicate notifications.
        if ($request->device_id) {
            DeviceToken::where('user_id', $user->id)
                ->where('device_id', '!=', $request->device_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        return response()->json([
            'success' => true,
            'message' => 'FCM token updated successfully',
        ]);
    }

    /**
     * Get all device tokens for current user
     */
    public function getDeviceTokens(Request $request)
    {
        $user = Auth::user();
        $tokens = DeviceToken::forUser($user->id)->get();

        return response()->json([
            'success' => true,
            'data' => $tokens,
        ]);
    }

    /**
     * Remove a specific device token (logout dari device tertentu)
     */
    public function removeDeviceToken(Request $request, $tokenId)
    {
        $user = Auth::user();
        
        $token = DeviceToken::where('id', $tokenId)
            ->where('user_id', $user->id)
            ->first();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Device token not found',
            ], 404);
        }

        $token->delete();

        return response()->json([
            'success' => true,
            'message' => 'Device token removed successfully',
        ]);
    }

    /**
     * Deactivate all other device tokens (logout dari semua device kecuali yang sekarang)
     */
    public function deactivateOtherDevices(Request $request)
    {
        $user = Auth::user();
        $currentToken = $request->fcm_token; // Token device yang sekarang aktif

        DeviceToken::forUser($user->id)
            ->where('fcm_token', '!=', $currentToken)
            ->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'All other devices have been deactivated',
        ]);
    }
}



