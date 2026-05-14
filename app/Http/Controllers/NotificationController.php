<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get unread notification count (JSON for AJAX polling).
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * Get latest notifications (JSON for dropdown).
     */
    public function latest()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'ticket_id' => $notification->data['ticket_id'] ?? null,
                    'ticket_number' => $notification->data['ticket_number'] ?? '',
                    'ticket_title' => $notification->data['ticket_title'] ?? '',
                    'responder_name' => $notification->data['responder_name'] ?? 'Admin',
                    'message_preview' => $notification->data['message_preview'] ?? '',
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a single notification as read and redirect to the ticket.
     */
    public function markAsRead(string $id)
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        $ticketId = $notification->data['ticket_id'] ?? null;

        if ($ticketId) {
            return redirect()->route('tickets.show', $ticketId);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
}
