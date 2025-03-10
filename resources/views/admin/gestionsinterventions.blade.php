@extends('layouts.admin')

@section('content')

<div class="bg-white shadow-md sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Gestion des Interventions Globale</h2>

    @if(session('success'))
        <div class="mb-4 p-3 text-green-800 bg-green-200 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
    <table class="min-w-full table-auto border-collapse border border-gray-300">
    <thead class="bg-gray-100">
                <tr class="bg-gray-100e">
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Utilisateur</th>
                    <th class="px-4 py-2 border">Titre</th>
                    <th class="px-4 py-2 border">Description</th>
                    <th class="px-4 py-2 border">Date</th>
                    <th class="px-4 py-2 border">Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($interventions as $intervention)
                    <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                        <td class="border px-4 py-2">{{ $intervention->id }}</td>
                        <td class="border px-4 py-2">{{ $intervention->user->name }}</td>
                        <td class="border px-4 py-2">{{ $intervention->titre }}</td>

                        <td class="border px-4 py-2">{{ $intervention->description }}</td>
                        <td class="border px-4 py-2">{{ $intervention->created_at->format('d/m/Y') }}</td>
                        

                        <td class="border px-4 py-2">
    @if ($intervention->status == 'En attente')
        <span class="px-2 py-1 bg-yellow-500 text-white rounded">En attente</span>
    @elseif ($intervention->status == 'En cours')
        <span class="px-2 py-1 bg-blue-500 text-white rounded">En cours</span>
    @elseif ($intervention->status == 'Terminé')
        <span class="px-2 py-1 bg-green-500 text-white rounded">Terminé</span>
    @endif
</td> 


<td class="border px-4 py-2">
    <button 
        class="assign-btn px-3 py-1 rounded text-white 
            @if($intervention->technicien_id) bg-green-500 @else bg-blue-500 @endif" 
        data-intervention-id="{{ $intervention->id }}"
        data-toggle="modal"
        data-target="#assignTechnicianModal">
        @if($intervention->technicien_id) Affecté @else Attribuer @endif
    </button>
</td>




                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</div>








@endsection
