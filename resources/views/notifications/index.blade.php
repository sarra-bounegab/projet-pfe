<!-- resources/views/notifications/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-md sm:rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Notifications</h2>
            @if(Auth::user()->unreadNotifications->count() > 0)
                <a href="{{ route('notifications.markAllAsRead') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Marquer tout comme lu
                </a>
            @endif
        </div>

        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="border border-gray-200 rounded-lg p-4 {{ is_null($notification->read_at) ? 'bg-blue-50 border-blue-200' : 'bg-white' }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="font-medium text-lg text-gray-800">{{ $notification->data['titre'] ?? 'Notification' }}</h3>
                                <p class="text-gray-700 mt-1">{{ $notification->data['message'] }}</p>

                                @if(isset($notification->data['changes']))
                                    <div class="mt-2">
                                        <span class="text-sm font-medium text-gray-600">Changements:</span>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @foreach($notification->data['changes'] as $field => $value)
                                                <span class="px-2 py-1 bg-gray-200 text-gray-800 text-xs rounded">{{ $field }}: {{ $value }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-3">
                                    <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <div class="flex space-x-2 ml-4">
                                @if(isset($notification->data['action_url']))
                                    <a href="{{ $notification->data['action_url'] }}" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition">
                                        Voir détails
                                    </a>
                                @endif
                                @if(is_null($notification->read_at))
                                    <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="px-3 py-1 bg-white text-blue-600 text-sm rounded border border-blue-600 hover:bg-blue-50 transition">
                                        Marquer comme lu
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="bg-gray-50 p-4 rounded-lg text-center text-gray-600">
                Aucune notification pour le moment.
            </div>
        @endif
    </div>
</div>
@endsection
