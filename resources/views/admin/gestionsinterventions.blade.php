@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md sm:rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-4">Gestion des Interventions (En cours et En attente)</h2>

    @if(session('success'))
        <div class="mb-4 p-3 text-green-800 bg-green-200 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tableau des interventions -->
    <div class="overflow-x-auto">
        <table id="interventionsTable" class="min-w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Utilisateur</th>
                    <th class="border px-4 py-2">Date</th>

                    <th class="border px-4 py-2">Statut</th>
                    <th class="border px-4 py-2">Actions</th>
                    <th class="border px-4 py-2">Techniciens assignés</th>
                    <th class="border px-4 py-2">Détails</th>
                </tr>
            </thead>
            <tbody>
                @foreach($interventions as $intervention)
                    @if($intervention->status !== 'Terminé')
                    <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                        <td class="border px-4 py-2">{{ $intervention->id }}</td>
                        <td class="border px-4 py-2">{{ $intervention->user->name }}</td>
                        <td class="border px-4 py-2">{{ $intervention->created_at->format('d/m/Y') }}</td>

                        <td class="border px-4 py-2 status-cell">
                            @if ($intervention->status == 'En attente')
                                <span class="px-2 py-1 bg-yellow-500 text-white rounded">En attente</span>
                            @elseif ($intervention->status == 'En cours')
                                <span class="px-2 py-1 bg-blue-500 text-white rounded">En cours</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2 action-cell">
                            <div class="flex flex-wrap gap-2">
                                @if ($intervention->status == 'En attente')
                                    <button class="assign-btn px-3 py-1 rounded text-white bg-blue-500 hover:bg-blue-600 transition"
                                            data-intervention-id="{{ $intervention->id }}">
                                        Attribuer
                                    </button>
                                @elseif ($intervention->status == 'En cours')
                                    <button class="cancel-assign-btn px-3 py-1 rounded text-white bg-red-500 hover:bg-red-600 transition"
                                            data-intervention-id="{{ $intervention->id }}"
                                            data-techniciens="{{ json_encode($intervention->techniciens->map(function($tech) {
                                                return ['id' => $tech->id, 'name' => $tech->name];
                                            })) }}">
                                        Annuler assignation
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td class="border px-4 py-2">
                            @if($intervention->techniciens->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($intervention->techniciens as $technicien)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                            {{ $technicien->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-500 text-sm italic">Aucun technicien assigné</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('interventions.show', $intervention->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Détails
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal d'assignation multiple -->
<div id="assignTechniciansModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white p-5 rounded-lg shadow-lg w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Attribuer des techniciens</h2>
            <button onclick="document.getElementById('assignTechniciansModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <form id="assignTechniciansForm" method="POST" action="{{ route('assign.multiple.technicians') }}">
            @csrf
            <input type="hidden" id="assign_intervention_id" name="intervention_id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sélectionner des techniciens :</label>
                <div class="max-h-60 overflow-y-auto border rounded p-2">
                    @foreach($techniciens as $technicien)
                    <div class="flex items-center mb-2">
                        <input type="checkbox" name="technicien_ids[]" id="assign_tech_{{ $technicien->id }}" value="{{ $technicien->id }}" class="mr-2">
                        <label for="assign_tech_{{ $technicien->id }}" class="text-sm">{{ $technicien->name }}</label>
                    </div>
                    @endforeach
                </div>
                <p id="assign_techniciens_error" class="text-red-500 text-xs mt-1 hidden">Veuillez sélectionner au moins un technicien.</p>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="document.getElementById('assignTechniciansModal').classList.add('hidden')" class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Annuler
                </button>
                <button type="submit" class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                    Confirmer l'assignation
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal pour annulation sélective des techniciens -->
<div id="cancelTechniciansModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white p-5 rounded-lg shadow-lg w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Annuler l'assignation des techniciens</h2>
            <button id="closeCancelModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <form id="cancelTechniciansForm" method="POST" action="{{ route('cancel.technicians') }}">
            @csrf
            <input type="hidden" id="cancel_intervention_id" name="intervention_id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sélectionner les techniciens à désassigner :</label>
                <div class="max-h-60 overflow-y-auto border rounded p-2" id="techniciensContainer">
                    <!-- Les techniciens seront chargés ici dynamiquement -->
                </div>
                <p id="cancel_techniciens_error" class="text-red-500 text-xs mt-1 hidden">Veuillez sélectionner au moins un technicien.</p>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" id="cancelCancelModal" class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Annuler
                </button>
                <button type="submit" class="px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                    Confirmer la désassignation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Fonction pour gérer l'assignation des techniciens
function setupAssignTechnicians() {
    // Gestion du bouton "Attribuer"
    document.querySelectorAll('.assign-btn').forEach(button => {
        button.addEventListener('click', function() {
            const interventionId = this.getAttribute('data-intervention-id');

            // Remplir le formulaire
            document.getElementById('assign_intervention_id').value = interventionId;

            // Décocher toutes les cases
            document.querySelectorAll('#assignTechniciansForm input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });

            // Afficher le modal
            document.getElementById('assignTechniciansModal').classList.remove('hidden');
        });
    });

    // Validation du formulaire d'assignation
    document.getElementById('assignTechniciansForm').addEventListener('submit', function(e) {
        const checkedBoxes = this.querySelectorAll('input[name="technicien_ids[]"]:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            document.getElementById('assign_techniciens_error').classList.remove('hidden');
        } else {
            document.getElementById('assign_techniciens_error').classList.add('hidden');
        }
    });
}

// Fonction pour gérer l'annulation d'assignation
function setupCancelTechnicians() {
    // Gestion du bouton "Annuler assignation"
    document.querySelectorAll('.cancel-assign-btn').forEach(button => {
        button.addEventListener('click', function() {
            const interventionId = this.getAttribute('data-intervention-id');
            const techniciens = JSON.parse(this.getAttribute('data-techniciens'));

            // Remplir le formulaire
            document.getElementById('cancel_intervention_id').value = interventionId;

            // Charger les techniciens
            const container = document.getElementById('techniciensContainer');
            container.innerHTML = '';

            if (techniciens.length > 0) {
                techniciens.forEach(tech => {
                    container.innerHTML += `
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="technicien_ids[]" id="cancel_tech_${tech.id}"
                                   value="${tech.id}" class="mr-2" checked>
                            <label for="cancel_tech_${tech.id}" class="text-sm">
                                ${tech.name}
                            </label>
                        </div>
                    `;
                });
            } else {
                container.innerHTML = '<p class="text-gray-500">Aucun technicien assigné</p>';
            }

            // Afficher le modal
            document.getElementById('cancelTechniciansModal').classList.remove('hidden');
        });
    });

    // Fermer le modal
    document.getElementById('closeCancelModal').addEventListener('click', function() {
        document.getElementById('cancelTechniciansModal').classList.add('hidden');
    });

    document.getElementById('cancelCancelModal').addEventListener('click', function() {
        document.getElementById('cancelTechniciansModal').classList.add('hidden');
    });

    // Validation du formulaire
    document.getElementById('cancelTechniciansForm').addEventListener('submit', function(e) {
        const checkedBoxes = this.querySelectorAll('input[name="technicien_ids[]"]:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            document.getElementById('cancel_techniciens_error').classList.remove('hidden');
        }
    });
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser DataTables
    $('#interventionsTable').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/French.json"
        },
        "responsive": true
    });


    setupAssignTechnicians();
    setupCancelTechnicians();
});
</script>
@endsection
