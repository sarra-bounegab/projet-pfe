@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 px-4">
    <div class="bg-white shadow-md rounded-xl overflow-hidden">
        <div class="bg-green-600 text-white px-6 py-4">
            <h3 class="text-xl font-semibold">Détails Complets de l'Intervention</h3>
        </div>

        <div class="p-6 space-y-6">
            <!-- Informations Générales -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                <h4 class="text-lg font-semibold border-b pb-2 mb-4">Informations Générales</h4>
                <div class="">
                    <div><strong>ID:</strong> {{ $intervention->id }}</div>
                    <div><strong>Titre:</strong> {{ $intervention->titre }}</div>
                    <div>
                        <strong>Statut:</strong>
                        <span class="inline-block px-3 py-1 rounded-full text-white text-sm font-medium
                            {{ $intervention->status === 'Terminé' ? 'bg-green-500' : ($intervention->status === 'En cours' ? 'bg-blue-500' : 'bg-yellow-400') }}">
                            {{ $intervention->status }}
                        </span>
                    </div>
                    <div><strong>Date Intervention:</strong> {{ \Carbon\Carbon::parse($intervention->date)->format('d/m/Y') }}</div>
                    <div><strong>Créée le:</strong> {{ $intervention->created_at->format('d/m/Y H:i') }}</div>
                    <div><strong>Modifiée le:</strong> {{ $intervention->updated_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="mt-4">
                    <strong>Description:</strong>
                    <p class="bg-white p-4 rounded-lg border text-gray-700 mt-2">{{ $intervention->description ?? 'Aucune description' }}</p>
                </div>
            </div>

            <!-- Détails Techniques -->
            @if($intervention->status === 'Terminé')
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                <h4 class="text-lg font-semibold border-b pb-2 mb-4">Détails Techniques</h4>
                @forelse($intervention->details as $detail)
                <div class="bg-white border rounded-lg p-4 mb-4 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div><strong>Type:</strong> {{ $detail->type->type ?? 'Non défini' }}</div>
                        <div><strong>Technicien:</strong> {{ $detail->technicien->name ?? 'Non assigné' }}</div>
                        
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                        <div>
                            <strong>details-technique:</strong>
                            <p class="text-gray-700">{{ $detail->contenu ?? 'Aucun contenu' }}</p>
                        </div>
                       
                    </div>
                </div>
                @empty
                <div class="bg-yellow-100 text-yellow-700 p-3 rounded-lg">Aucun détail technique enregistré</div>
                @endforelse
            </div>
            @endif

            <!-- Historique des Actions -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                <h4 class="text-lg font-semibold border-b pb-2 mb-4">Historique des Actions</h4>
                @forelse($intervention->historiques as $historique)
                <div class="bg-white p-4 rounded-lg shadow-sm border mb-4">
                    <div class="text-sm text-gray-500 mb-1">
                        <strong>Date :</strong> {{ \Carbon\Carbon::parse($historique->created_at)->format('d/m/Y H:i') }}
                    </div>
                    <p class="text-gray-800">{{ $historique->action }}</p>
                </div>
                @empty
                <div class="bg-yellow-100 text-yellow-700 p-3 rounded-lg">Aucune action historique enregistrée.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Boutons -->
    <div class="mt-6 flex flex-col md:flex-row items-start md:items-center gap-4">
        <a href="{{ route('intervention.print', $intervention->id) }}"
            class="inline-flex items-center px-5 py-2 bg-green-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow">
            🖨️ Imprimer le rapport
        </a>

        @php
            $profileId = Auth::check() ? Auth::user()->profile_id : null;
        @endphp

        @if ($profileId === 1)
            <a href="{{ route('admin.gestionsinterventions') }}" class="text-sm text-gray-600 hover:underline">← Retour (Admin)</a>
        @elseif ($profileId === 2)
            <a href="{{ route('technician.gestionsinterventions') }}" class="text-sm text-gray-600 hover:underline">← Retour (Technicien)</a>
        @elseif ($profileId === 3)
            <a href="{{ route('user.gestionsinterventions') }}" class="text-sm text-gray-600 hover:underline">← Retour (Utilisateur)</a>
        @endif
    </div>
</div>
@endsection
