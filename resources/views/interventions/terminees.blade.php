@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md sm:rounded-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Gestion des Interventions Globale</h2>
        
        <button id="clearHistoryBtn" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
            <i class="fas fa-trash-alt mr-2"></i>Vider l'historique
        </button>
    </div>

    <!-- Tableau des interventions -->
    <div class="overflow-x-auto">
        <table id="interventionsTable" class="min-w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">
                        <input type="checkbox" id="selectAllCheckbox" class="form-checkbox h-4 w-4 text-blue-600">
                    </th>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Utilisateur</th>
                    <th class="border px-4 py-2">Date</th>
                    <th class="border px-4 py-2">Statut</th>
                    <th class="border px-4 py-2">Détails</th>
                </tr>
            </thead>
            <tbody>
                @foreach($interventions as $intervention)
                <tr data-id="{{ $intervention->id }}" class="@if($intervention->status === 'Terminé') terminée @endif">
                    <td class="border px-4 py-2 text-center">
                        @if($intervention->status === 'Terminé')
                            <input type="checkbox" class="intervention-checkbox form-checkbox h-4 w-4 text-blue-600">
                        @endif
                    </td>
                    <td class="border px-4 py-2">{{ $intervention->id }}</td>
                    <td class="border px-4 py-2">{{ $intervention->user->name }}</td>
                    <td class="border px-4 py-2">{{ $intervention->created_at->format('d/m/Y') }}</td>
                    <td class="border px-4 py-2">
                        @if($intervention->status == 'En attente')
                            <span class="px-2 py-1 bg-yellow-500 text-white rounded">En attente</span>
                        @elseif($intervention->status == 'En cours')
                            <span class="px-2 py-1 bg-blue-500 text-white rounded">En cours</span>
                        @elseif($intervention->status == 'Terminé')
                            <span class="px-2 py-1 bg-green-500 text-white rounded">Terminé</span>
                        @endif
                    </td>
                    <td class="border px-4 py-2">
                         <a href="{{ route('interventions.show', $intervention->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                                <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition" onclick="return confirm('Voulez-vous vraiment réouvrir cette intervention ?')">
                                            Réouvrir
                                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de confirmation -->
<div id="clearHistoryModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white p-5 rounded-lg shadow-lg w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Confirmation</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        
        <div>
            <p class="mb-4">Êtes-vous sûr de vouloir supprimer les interventions sélectionnées de cette vue ?</p>
            
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()" class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Annuler
                </button>
                <button type="button" id="confirmClearBtn" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                    Confirmer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales
let dataTable;

document.addEventListener('DOMContentLoaded', function() {
    // Initialisation de DataTable
    dataTable = $('#interventionsTable').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/French.json"
        },
        "columnDefs": [
            { "orderable": false, "targets": [0, 5] }, 
            { "className": "dt-center", "targets": [0] } 
        ]
    });

    
    $('#selectAllCheckbox').on('change', function() {
        $('.intervention-checkbox').prop('checked', this.checked);
    });

   
    $('#clearHistoryBtn').on('click', function() {
        const selectedCount = $('.intervention-checkbox:checked').length;
        
        if (selectedCount === 0) {
            alert('Veuillez sélectionner au moins une intervention terminée');
            return;
        }
        
        $('#clearHistoryModal').removeClass('hidden');
    });

    
    $('#confirmClearBtn').on('click', function() {
       
        $('.intervention-checkbox:checked').each(function() {
            const row = $(this).closest('tr');
            dataTable.row(row).remove().draw(false);
        });
        
        
        $('#selectAllCheckbox').prop('checked', false);
        
        // Fermer le modal
        closeModal();
    });
});

function closeModal() {
    $('#clearHistoryModal').addClass('hidden');
}
</script>

<style>

.dt-remove {
    display: none !important;
}
</style>
@endsection