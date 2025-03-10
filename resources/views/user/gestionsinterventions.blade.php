@extends('layouts.user')

@section('content')
    <div class="py-6 h-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Gestion des Interventions</h2>

                <!-- Afficher les interventions -->
                <table class="min-w-full table-auto border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">#</th>
                            <th class="px-4 py-2 border">Titre</th>
                            <th class="px-4 py-2 border">Description</th>
                            <th class="px-4 py-2 border">Type d'intervention</th>
                            <th class="px-4 py-2 border">Statut</th>
                            <th class="px-4 py-2 border">Actions</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interventions as $intervention)
                            <tr class="text-center hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2">{{ $intervention->titre }}</td>

                                <td class="border px-4 py-2">{{ $intervention->description }}</td>
                                
                                <!-- Affichage du type d'intervention (Matériel ou Logiciel) -->
                                <td class="border px-4 py-2">
    @if ($intervention->type_intervention_id == 1)
        Matériel
    @elseif ($intervention->type_intervention_id == 2)
        Logiciel
    @else
        Inconnu
    @endif
</td>


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
 
 
</td>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Aucune intervention trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Lien vers la page de création d'une nouvelle intervention -->
             


            </div>
        </div>
    </div>
    @endsection
