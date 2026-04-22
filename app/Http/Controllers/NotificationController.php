<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // GET /api/notifications
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()->notifications()->paginate(20);

        return $this->success($notifications);
    }

    // GET /api/notifications/unread-count
    public function unreadCount(Request $request): JsonResponse
    {
        $count = $request->user()->unreadNotifications()->count();

        return $this->success(['count' => $count]);
    }

    // POST /api/notifications/{id}/read
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);

        $notification->markAsRead();

        return $this->success(null, 'Notification marked as read');
    }

    // POST /api/notifications/read-all
    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return $this->success(null, 'All notifications marked as read');
    }
}