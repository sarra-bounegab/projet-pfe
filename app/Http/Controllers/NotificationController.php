<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return redirect()->back();
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }


    public function showDetails($id)
{
    $notification = Auth::user()->notifications()->findOrFail($id);

    if (!$notification) {
        return redirect()->back()->with('error', 'Notification non trouvée.');
    }

    // Marquer comme lue
    $notification->markAsRead();

    // Récupérer l'ID de l'intervention depuis les données de la notification
    $interventionId = $notification->data['intervention_id'] ?? null;

    if (!$interventionId) {
        return redirect()->back()->with('error', 'Données d\'intervention manquantes.');
    }

    // Rediriger vers la page de détails de l'intervention
    return redirect()->route('intervention.details', ['id' => $interventionId]);
}

}
