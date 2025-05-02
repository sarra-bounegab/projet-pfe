@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- En-tête -->
        <div class="bg-gray-800 text-white px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Détails de l'intervention #{{ $intervention->id }}</h1>
                <div class="flex space-x-2">
                    @if($intervention->status === 'Terminé')
                        <button onclick="printDetails()" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded text-sm flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Imprimer
                        </button>
                    @endif
                    <a href="{{ route('interventions.index') }}" class="px-3 py-1 bg-gray-600 hover:bg-gray-700 rounded text-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations principales -->
        <div class="p-6 border-b">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Demandeur</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $intervention->user->name }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Date de création</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $intervention->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Titre</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $intervention->titre }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Statut</h3>
                    <p class="mt-1">
                        @if ($intervention->status === 'Terminé')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Terminé</span>
                        @elseif ($intervention->status === 'En cours')
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">En cours</span>
                        @else
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">En attente</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <h3 class="text-sm font-medium text-gray-500">Description initiale</h3>
                <div class="mt-1 p-3 bg-gray-50 rounded text-sm text-gray-700">
                    {{ $intervention->description }}
                </div>
            </div>
        </div>

        <!-- Détails techniques (visible seulement si terminé) -->
        @if($intervention->status === 'Terminé' && $intervention->details->count() > 0)
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold mb-4">Détails techniques</h2>
            
            @foreach($intervention->details as $detail)
            <div class="mb-6 last:mb-0">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-md font-medium text-gray-700">
                        {{ $detail->typeIntervention->nom }}
                    </h3>
                    <span class="text-xs text-gray-500">
                        Ajouté le {{ $detail->created_at->format('d/m/Y H:i') }}
                        @if($detail->technicien)
                            par {{ $detail->technicien->name }}
                        @endif
                    </span>
                </div>
                <div class="p-3 bg-gray-50 border border-gray-200 rounded text-sm text-gray-700 whitespace-pre-line">
                    {{ $detail->contenu }}
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Historique des statuts -->
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Historique</h2>
            <div class="space-y-4">
                @foreach($intervention->historiques as $historique)
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800">
                            <span class="font-medium">{{ $historique->user->name }}</span> a changé le statut en 
                            <span class="font-medium">
                                @if ($historique->new_status === 'Terminé')
                                    <span class="text-green-600">Terminé</span>
                                @elseif ($historique->new_status === 'En cours')
                                    <span class="text-blue-600">En cours</span>
                                @else
                                    <span class="text-yellow-600">En attente</span>
                                @endif
                            </span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $historique->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
function printDetails() {
    window.print();
}
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .container, .container * {
        visibility: visible;
    }
    .container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print {
        display: none !important;
    }
}
</style>
@endsection