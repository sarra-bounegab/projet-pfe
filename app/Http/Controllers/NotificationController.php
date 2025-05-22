<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->middleware('auth'); // Protection des routes
    }

    /**
     * Afficher la page des notifications
     */
    public function index()
    {
        $user = Auth::user();

        // Récupérer les notifications paginées
        $notifications = $this->notificationService->getUserNotifications($user->id);

        // Compter les notifications non lues
        $unreadCount = $this->notificationService->getUnreadCount($user->id);

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Récupérer les notifications récentes (pour le dropdown)
     */
    public function getRecent()
    {
        $user = Auth::user();
        $notifications = $this->notificationService->getRecentNotifications($user->id, 5);
        $unreadCount = $this->notificationService->getUnreadCount($user->id);

        return response()->json([
            'notifications' => $notifications->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'time_ago' => $notification->created_at->diffForHumans(),
                    'is_read' => (bool)$notification->read_at,
                    'icon' => $notification->data['icon'] ?? 'bell',
                    'color' => $notification->data['color'] ?? 'primary',
                    'intervention_id' => $notification->data['intervention_id'] ?? null,
                    'url' => $notification->data['url'] ?? '#'
                ];
            }),
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('notifiable_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => $this->notificationService->getUnreadCount(Auth::id())
        ]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(Auth::id());

        return response()->json([
            'success' => true,
            'unread_count' => 0
        ]);
    }

    /**
     * Supprimer une notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('notifiable_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'success' => true,
            'unread_count' => $this->notificationService->getUnreadCount(Auth::id())
        ]);
    }

    /**
     * Obtenir le nombre de notifications non lues (pour AJAX)
     */
    public function getUnreadCount()
    {
        $count = $this->notificationService->getUnreadCount(Auth::id());

        return response()->json(['count' => $count]);
    }
}
