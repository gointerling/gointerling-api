<?php

namespace App\Http\Controllers;


use App\Helpers\ApiResponse;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Store a custom notification in the database for the authenticated user.
     *
     * @param  string  $message
     * @param  string  $link
     * @return \Illuminate\Http\Response
     */
    public function storeNotification($message, $link, $userId)
    {
        try {
            // Store notification
            $notification = Notification::create([
                'user_id' => $userId,
                'notification' => $message,
                'notification_link' => $link,
                'is_read' => false,
            ]);

            return ApiResponse::send(
                200,
                compact('notification'),
                'Notification stored successfully!'
            );
        } catch (Exception $e) {
            return ApiResponse::send(
                500,
                null,
                'Failed to store notification. Error: ' . $e->getMessage()
            );
        }
    }

    /**
     * Get all notifications for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserNotifications()
    {
        try {
            // Get the authenticated user ID
            $userId = Auth::id();

            // Fetch notifications for the authenticated user, prioritizing unread, and limit to 5
            $notifications = Notification::where('user_id', $userId)
                ->orderBy('is_read', 'asc')  // Prioritize unread notifications
                ->orderBy('created_at', 'desc')  // Then order by the most recent
                ->limit(5)  // Limit the result to 5 notifications
                ->get();

            return ApiResponse::send(
                200,
                compact('notifications'),
                'Notifications retrieved successfully.'
            );
        } catch (Exception $e) {
            return ApiResponse::send(
                500,
                null,
                'Failed to retrieve notifications. Error: ' . $e->getMessage()
            );
        }
    }

    /**
     * Mark a specific notification as read for the authenticated user.
     *
     * @param  int  $notificationId
     * @return \Illuminate\Http\Response
     */
    public function markAsRead($notificationId)
    {
        try {
            $userId = Auth::id();

            // Find the notification by ID and check ownership
            $notification = Notification::where('id', $notificationId)
                ->where('user_id', $userId)
                ->first();

            if (!$notification) {
                return ApiResponse::send(
                    404,
                    null,
                    'Notification not found.'
                );
            }

            // Mark as read
            $notification->update(['is_read' => true]);

            return ApiResponse::send(
                200,
                compact('notification'),
                'Notification marked as read successfully.'
            );
        } catch (Exception $e) {
            return ApiResponse::send(
                500,
                null,
                'Failed to mark notification as read. Error: ' . $e->getMessage()
            );
        }
    }

    /**
     * Delete a specific notification for the authenticated user.
     *
     * @param  int  $notificationId
     * @return \Illuminate\Http\Response
     */
    public function deleteNotification($notificationId)
    {
        try {
            $userId = Auth::id();

            // Find the notification by ID and check ownership
            $notification = Notification::where('id', $notificationId)
                ->where('user_id', $userId)
                ->first();

            if (!$notification) {
                return ApiResponse::send(
                    404,
                    null,
                    'Notification not found.'
                );
            }

            // Delete the notification
            $notification->delete();

            return ApiResponse::send(
                200,
                null,
                'Notification deleted successfully.'
            );
        } catch (Exception $e) {
            return ApiResponse::send(
                500,
                null,
                'Failed to delete notification. Error: ' . $e->getMessage()
            );
        }
    }

    /**
     * Get the count of unread notifications for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUnreadNotificationCount()
    {
        try {
            $userId = Auth::id();

            // Count the unread notifications for the authenticated user
            $unreadCount = Notification::where('user_id', $userId)
                ->where('is_read', false)
                ->count();

            return ApiResponse::send(
                200,
                compact('unreadCount'),
                'Unread notifications count retrieved successfully.'
            );
        } catch (Exception $e) {
            return ApiResponse::send(
                500,
                null,
                'Failed to retrieve unread notification count. Error: ' . $e->getMessage()
            );
        }
    }

    /**
     * Mark all notifications as read for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function markAll()
    {
        try {
            $userId = Auth::id();

            // Mark all notifications as read for the authenticated user
            Notification::where('user_id', $userId)
                ->update(['is_read' => true]);

            return ApiResponse::send(
                200,
                null,
                'All notifications marked as read successfully.'
            );
        } catch (Exception $e) {
            return ApiResponse::send(
                500,
                null,
                'Failed to mark all notifications as read. Error: ' . $e->getMessage()
            );
        }
    }
}
