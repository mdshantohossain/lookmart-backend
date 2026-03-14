<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        isAuthorized('notification module');

        $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.notification.index', compact('notifications'));
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All read notifications have been marked as read.');
    }

    public function deleteNotification($id)
    {

    }
}
