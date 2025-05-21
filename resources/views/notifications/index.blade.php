@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-md sm:rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-800">Mes Notifications</h2>
            <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                    Tout marquer comme lu
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 text-green-800 bg-green-200 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if ($notifications->count())
            <div class="space-y-2">
                @foreach ($notifications as $notification)
                    <a href="{{ route('notifications.showDetails', $notification->id) }}"
                       class="block border rounded-lg hover:bg-gray-50 transition {{ is_null($notification->read_at) ? 'bg-blue-50 border-blue-200' : 'border-gray-200' }}">
                        <div class="flex justify-between items-center p-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    @if(is_null($notification->read_at))
                                        <span class="inline-flex h-2 w-2 rounded-full bg-blue-600"></span>
                                    @else
                                        <span class="inline-flex h-2 w-2 rounded-full bg-gray-300"></span>
                                    @endif
                                </div>
                                <div>
                                    <p class="{{ is_null($notification->read_at) ? 'font-medium' : 'font-normal' }} text-gray-800">
                                        {{ $notification->data['message'] ?? 'Nouvelle notification' }}
                                    </p>
                                    @if(isset($notification->data['details']))
                                        <p class="text-sm text-gray-600 mt-1">{{ $notification->data['details'] }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                @if(isset($notification->data['type']))
                                    @if($notification->data['type'] == 'intervention')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-md">Intervention</span>
                                    @elseif($notification->data['type'] == 'system')
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-md">Système</span>
                                    @elseif($notification->data['type'] == 'alert')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-md">Alerte</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="bg-gray-50 p-8 text-center rounded-lg border border-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <p class="text-gray-600 text-lg">Vous n'avez aucune notification pour le moment.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal pour les détails de la notification -->
<div id="notificationDetailsModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-lg border border-gray-200 relative">

        <!-- Bouton de fermeture -->
        <button type="button" onclick="closeNotificationDetailsModal()"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>

        <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Détails de la notification</h3>

        <div class="space-y-4 text-sm text-gray-700">
            <div id="notification-content" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <!-- Le contenu sera injecté par JavaScript -->
            </div>

            <div class="grid grid-cols-4 gap-2">
                <div class="col-span-1">
                    <span class="font-medium text-gray-900">Date:</span>
                </div>
                <div class="col-span-3">
                    <span id="notification-date"></span>
                </div>
            </div>

            <div id="notification-related-content" class="mt-4 hidden">
                <div class="font-medium text-gray-900 mb-2">Contenu associé:</div>
                <div id="notification-related-data" class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <!-- Le contenu associé sera injecté par JavaScript -->
                </div>
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <form id="markAsReadForm" method="POST" class="hidden">
                @csrf
                <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm transition">
                    Marquer comme lu
                </button>
            </form>

            <button type="button" onclick="closeNotificationDetailsModal()"
                    class="px-5 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg text-sm transition">
                Fermer
            </button>

            <a href="#" id="relatedActionLink" class="hidden px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                Voir l'élément
            </a>
        </div>
    </div>
</div>

<script>
    function openNotificationDetailsModal(id) {
        fetch(`/notifications/${id}/details`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('notification-content').innerHTML = data.message;
                document.getElementById('notification-date').textContent = data.created_at;

                // Si la notification n'est pas lue, afficher le bouton pour la marquer comme lue
                if (!data.read_at) {
                    document.getElementById('markAsReadForm').classList.remove('hidden');
                    document.getElementById('markAsReadForm').action = `/notifications/${id}/mark-as-read`;
                } else {
                    document.getElementById('markAsReadForm').classList.add('hidden');
                }

                // Si la notification a un contenu associé
                if (data.related_data) {
                    document.getElementById('notification-related-content').classList.remove('hidden');
                    document.getElementById('notification-related-data').innerHTML = data.related_data;

                    // Si la notification a un lien d'action
                    if (data.action_url) {
                        document.getElementById('relatedActionLink').classList.remove('hidden');
                        document.getElementById('relatedActionLink').href = data.action_url;
                        document.getElementById('relatedActionLink').textContent = data.action_text || "Voir l'élément";
                    } else {
                        document.getElementById('relatedActionLink').classList.add('hidden');
                    }
                } else {
                    document.getElementById('notification-related-content').classList.add('hidden');
                    document.getElementById('relatedActionLink').classList.add('hidden');
                }

                // Afficher le modal
                document.getElementById('notificationDetailsModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors du chargement des détails de la notification.');
            });
    }

    function closeNotificationDetailsModal() {
        document.getElementById('notificationDetailsModal').classList.add('hidden');
    }

    // Ajouter cet événement listener pour charger les détails lors du clic direct sur une notification
    document.addEventListener('DOMContentLoaded', function() {
        // Si l'URL contient un ID de notification (par exemple: ?notification=123)
        const urlParams = new URLSearchParams(window.location.search);
        const notificationId = urlParams.get('notification');

        if (notificationId) {
            openNotificationDetailsModal(notificationId);
        }
    });
</script>
@endsection
