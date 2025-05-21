@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md sm:rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-4">Liste des interventions clôturées</h2>

    @if(session('success'))
        <div class="mb-4 p-3 text-green-800 bg-green-200 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Utilisateur</th>
                    <th class="border px-4 py-2">Date Clôture</th>
                    <th class="border px-4 py-2">Type d'intervention</th>
                    <th class="border px-4 py-2">Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($interventions as $intervention)
                    <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                        <td class="border px-4 py-2">{{ $intervention->id }}</td>
                        <td class="border px-4 py-2">{{ $intervention->user->name }}</td>
                        <td class="border px-4 py-2">{{ $intervention->updated_at->format('d/m/Y') }}</td>
                        <td class="border px-4 py-2">{{ $intervention->typeIntervention ? $intervention->typeIntervention->type : 'Non défini' }}</td>
                        <td class="border px-4 py-2">
                            <span class="px-2 py-1 bg-green-500 text-white rounded">Terminé</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
