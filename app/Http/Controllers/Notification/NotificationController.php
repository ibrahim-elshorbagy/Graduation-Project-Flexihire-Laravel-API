<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{

    public function getAllNotifications(Request $request)
    {
        try {
            $user = $request->user();
            $notifications = $user->notifications;

            return response()->json([
                'status' => true,
                'notifications' => $notifications,
                'unread_count' => $user->unreadNotifications->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function markAsRead(Request $request, $id)
    {
        // Validate notification ID
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|string|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid notification ID',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $notification = $user->notifications()->where('id', $id)->first();

            if (!$notification) {
                return response()->json([
                    'status' => false,
                    'message' => 'Notification not found'
                ], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'status' => true,
                'message' => 'Notification marked as read',
                'unread_count' => $user->unreadNotifications->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to mark notification as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function deleteNotification(Request $request, $id)
    {
        // Validate notification ID
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|string|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid notification ID',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $notification = $user->notifications()->where('id', $id)->first();

            if (!$notification) {
                return response()->json([
                    'status' => false,
                    'message' => 'Notification not found'
                ], 404);
            }

            $notification->delete();

            return response()->json([
                'status' => true,
                'message' => 'Notification deleted',
                'unread_count' => $user->unreadNotifications->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function markAllAsRead(Request $request)
    {
        try {
            $user = $request->user();

            // Check if there are any unread notifications
            if ($user->unreadNotifications->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'No unread notifications to mark',
                    'unread_count' => 0
                ]);
            }

            $user->unreadNotifications->markAsRead();

            return response()->json([
                'status' => true,
                'message' => 'All notifications marked as read',
                'unread_count' => 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to mark all notifications as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function deleteAllNotifications(Request $request)
    {
        try {
            $user = $request->user();

            // Check if there are any notifications to delete
            if ($user->notifications->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'No notifications to delete',
                ]);
            }

            // Delete all notifications
            $user->notifications()->delete();

            return response()->json([
                'status' => true,
                'message' => 'All notifications deleted successfully',
                'unread_count' => 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete all notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
