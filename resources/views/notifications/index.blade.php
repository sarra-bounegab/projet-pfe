<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <!-- Notifications Page -->
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <i class="fas fa-bell text-gray-600 text-xl mr-3"></i>
                        <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
                        <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800" id="total-unread">
                            {{ $unreadCount }} non lues
                        </span>
                    </div>
                    <button onclick="markAllAsRead()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-check-double mr-2"></i>
                        Marquer toutes comme lues
                    </button>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <!-- Filter Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button onclick="filterNotifications('all')" class="filter-tab active px-6 py-3 text-sm font-medium text-blue-600 border-b-2 border-blue-500 focus:outline-none">
                            Toutes <span class="ml-1 text-gray-500">({{ $notifications->total() }})</span>
                        </button>
                        <button onclick="filterNotifications('unread')" class="filter-tab px-6 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 focus:outline-none">
                            Non lues <span class="ml-1 text-red-500">({{ $unreadCount }})</span>
                        </button>
                        <button onclick="filterNotifications('read')" class="filter-tab px-6 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 focus:outline-none">
                            Lues <span class="ml-1 text-gray-500">({{ $notifications->total() - $unreadCount }})</span>
                        </button>
                    </nav>
                </div>

                <!-- Notifications -->
                <div class="divide-y divide-gray-200" id="notifications-container">
                    @forelse($notifications as $notification)
                        <div class="notification-item {{ $notification->is_read ? 'hover:bg-gray-50' : 'bg-blue-50 hover:bg-blue-100' }} transition-colors cursor-pointer"
                             data-read="{{ $notification->is_read ? 'true' : 'false' }}"
                             data-id="{{ $notification->id }}"
                             onclick="markAsRead({{ $notification->id }})">
                            <div class="px-6 py-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 {{ $notification->color ?? 'bg-blue-100' }} rounded-full flex items-center justify-center">
                                            <i class="fas {{ $notification->icon ?? 'fa-bell' }} {{ str_replace('bg-', 'text-', $notification->color ?? 'text-blue-600') }}"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium {{ $notification->is_read ? 'text-gray-700' : 'text-gray-900' }}">
                                                {{ $notification->title }}
                                            </p>
                                            <div class="flex items-center">
                                                @if(!$notification->is_read)
                                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                                @endif
                                                <p class="text-xs text-gray-500">{{ $notification->time_ago }}</p>
                                            </div>
                                        </div>
                                        <p class="text-sm {{ $notification->is_read ? 'text-gray-500' : 'text-gray-600' }} mt-1">
                                            {{ $notification->message }}
                                        </p>
                                        @if($notification->sender || $notification->intervention)
                                            <div class="flex items-center mt-2 text-xs {{ $notification->is_read ? 'text-gray-400' : 'text-gray-500' }}">
                                                @if($notification->sender)
                                                    <i class="fas fa-user mr-1"></i>
                                                    <span>Par {{ $notification->sender->name }}</span>
                                                @endif
                                                @if($notification->intervention)
                                                    <span class="mx-2">•</span>
                                                    <i class="fas fa-hashtag mr-1"></i>
                                                    <span>{{ $notification->intervention->reference }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <button onclick="deleteNotification({{ $notification->id }}, event)" class="text-gray-400 hover:text-red-500 transition-colors">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-bell-slash text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500 text-lg mb-2">Aucune notification</p>
                            <p class="text-gray-400">Vous n'avez pas encore de notifications.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Empty State (for filtered results) -->
                <div id="empty-state" class="hidden text-center py-12">
                    <i class="fas fa-bell-slash text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500 text-lg mb-2">Aucune notification trouvée</p>
                    <p class="text-gray-400">Aucune notification ne correspond à ce filtre.</p>
                </div>

                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 flex justify-between sm:hidden">
                                @if($notifications->onFirstPage())
                                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                        Précédent
                                    </span>
                                @else
                                    <a href="{{ $notifications->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                        Précédent
                                    </a>
                                @endif

                                @if($notifications->hasMorePages())
                                    <a href="{{ $notifications->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                        Suivant
                                    </a>
                                @else
                                    <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                        Suivant
                                    </span>
                                @endif
                            </div>

                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700 leading-5">
                                        Affichage de
                                        <span class="font-medium">{{ $notifications->firstItem() }}</span>
                                        à
                                        <span class="font-medium">{{ $notifications->lastItem() }}</span>
                                        sur
                                        <span class="font-medium">{{ $notifications->total() }}</span>
                                        résultats
                                    </p>
                                </div>
                                <div>
                                    <span class="relative z-0 inline-flex shadow-sm rounded-md">
                                        {{ $notifications->links() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Configuration CSRF pour les requêtes AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Fonction pour marquer une notification comme lue
        function markAsRead(notificationId) {
            const notificationElement = document.querySelector(`[data-id="${notificationId}"]`);

            // Si déjà lue, ne rien faire
            if (notificationElement.getAttribute('data-read') === 'true') {
                return;
            }

            fetch(route('notifications.markAsRead', { id: notificationId
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            }))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour l'apparence de la notification
                    notificationElement.setAttribute('data-read', 'true');
                    notificationElement.className = notificationElement.className
                        .replace('bg-blue-50 hover:bg-blue-100', 'hover:bg-gray-50');

                    // Supprimer le point bleu
                    const blueDot = notificationElement.querySelector('.w-2.h-2.bg-blue-500');
                    if (blueDot) {
                        blueDot.remove();
                    }

                    // Mettre à jour les couleurs du texte
                    const title = notificationElement.querySelector('.text-gray-900');
                    if (title) {
                        title.className = title.className.replace('text-gray-900', 'text-gray-700');
                    }

                    const message = notificationElement.querySelector('.text-gray-600');
                    if (message) {
                        message.className = message.className.replace('text-gray-600', 'text-gray-500');
                    }

                    const details = notificationElement.querySelector('.text-gray-500');
                    if (details) {
                        details.className = details.className.replace('text-gray-500', 'text-gray-400');
                    }

                    // Mettre à jour le compteur
                    updateUnreadCount();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la mise à jour de la notification');
            });
        }

        // Fonction pour marquer toutes les notifications comme lues
        function markAllAsRead() {
            fetch('/notifications/mark-all-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour toutes les notifications non lues
                    document.querySelectorAll('[data-read="false"]').forEach(element => {
                        element.setAttribute('data-read', 'true');
                        element.className = element.className
                            .replace('bg-blue-50 hover:bg-blue-100', 'hover:bg-gray-50');

                        // Supprimer le point bleu
                        const blueDot = element.querySelector('.w-2.h-2.bg-blue-500');
                        if (blueDot) {
                            blueDot.remove();
                        }

                        // Mettre à jour les couleurs du texte
                        const title = element.querySelector('.text-gray-900');
                        if (title) {
                            title.className = title.className.replace('text-gray-900', 'text-gray-700');
                        }

                        const message = element.querySelector('.text-gray-600');
                        if (message) {
                            message.className = message.className.replace('text-gray-600', 'text-gray-500');
                        }

                        const details = element.querySelector('.text-gray-500');
                        if (details) {
                            details.className = details.className.replace('text-gray-500', 'text-gray-400');
                        }
                    });

                    // Mettre à jour le compteur
                    updateUnreadCount();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la mise à jour des notifications');
            });
        }

        // Fonction pour supprimer une notification
        function deleteNotification(notificationId, event) {
            event.stopPropagation(); // Empêcher le clic sur la notification parent

            if (!confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) {
                return;
            }

            fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Supprimer l'élément du DOM avec animation
                    const notificationElement = document.querySelector(`[data-id="${notificationId}"]`);
                    notificationElement.style.transition = 'opacity 0.3s ease-out';
                    notificationElement.style.opacity = '0';

                    setTimeout(() => {
                        notificationElement.remove();

                        // Vérifier s'il reste des notifications
                        const remainingNotifications = document.querySelectorAll('.notification-item');
                        if (remainingNotifications.length === 0) {
                            document.getElementById('empty-state').classList.remove('hidden');
                        }

                        // Mettre à jour le compteur
                        updateUnreadCount();
                    }, 300);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la suppression de la notification');
            });
        }

        // Fonction pour filtrer les notifications
        function filterNotifications(filter) {
            const notifications = document.querySelectorAll('.notification-item');
            const emptyState = document.getElementById('empty-state');
            let visibleCount = 0;

            // Mettre à jour les onglets
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active', 'text-blue-600', 'border-blue-500');
                tab.classList.add('text-gray-500', 'border-transparent');
            });

            event.target.classList.add('active', 'text-blue-600', 'border-blue-500');
            event.target.classList.remove('text-gray-500', 'border-transparent');

            notifications.forEach(notification => {
                const isRead = notification.getAttribute('data-read') === 'true';
                let shouldShow = false;

                switch(filter) {
                    case 'all':
                        shouldShow = true;
                        break;
                    case 'unread':
                        shouldShow = !isRead;
                        break;
                    case 'read':
                        shouldShow = isRead;
                        break;
                }

                if (shouldShow) {
                    notification.style.display = 'block';
                    visibleCount++;
                } else {
                    notification.style.display = 'none';
                }
            });

            // Afficher l'état vide si aucune notification visible
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        // Fonction pour mettre à jour le compteur de notifications non lues
        function updateUnreadCount() {
            fetch('/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    const totalUnreadElement = document.getElementById('total-unread');
                    const count = data.count;

                    if (count === 0) {
                        totalUnreadElement.textContent = 'Toutes lues';
                        totalUnreadElement.className = 'ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
                    } else {
                        totalUnreadElement.textContent = `${count} non lues`;
                        totalUnreadElement.className = 'ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
                    }

                    // Mettre à jour les compteurs dans les onglets
                    const unreadTab = document.querySelector('.filter-tab:nth-child(2) span');
                    const readTab = document.querySelector('.filter-tab:nth-child(3) span');
                    const allTab = document.querySelector('.filter-tab:nth-child(1) span');

                    if (unreadTab) {
                        unreadTab.textContent = `(${count})`;
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la mise à jour du compteur:', error);
                });
        }

        // Actualiser le compteur au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            updateUnreadCount();
        });


    </script>
</body>
</html>
