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
        <table id="interventionsTable" class="min-w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Utilisateur</th>
                   
                    <th class="border px-4 py-2">Date</th>
                    <th class="border px-4 py-2">Type d'intervention</th>
                    <th class="border px-4 py-2">Statut</th>
                    <th class="border px-4 py-2">Actions</th>
                    <th class="border px-4 py-2">Détails</th>
                </tr>
            </thead>
            <tbody>
                @foreach($interventions as $intervention)
                    <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                        <td class="border px-4 py-2">{{ $intervention->id }}</td>
                        <td class="border px-4 py-2">{{ $intervention->user->name }}</td>
                      
                        <td class="border px-4 py-2">{{ $intervention->created_at->format('d/m/Y') }}</td>
                        <td class="border px-4 py-2">
                            {{ $intervention->typeIntervention ? $intervention->typeIntervention->type : 'Non défini' }}
                        </td>
                        <td class="border px-4 py-2 status-cell">
                            @if ($intervention->status == 'En attente')
                                <span class="px-2 py-1 bg-yellow-500 text-white rounded">En attente</span>
                            @elseif ($intervention->status == 'En cours')
                                <span class="px-2 py-1 bg-blue-500 text-white rounded">En cours</span>
                            @elseif ($intervention->status == 'Terminé')
                                <span class="px-2 py-1 bg-green-500 text-white rounded">Terminé</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2 action-cell">
                            @if ($intervention->status == 'En attente')
                                <button class="assign-btn px-3 py-1 rounded text-white bg-blue-500" 
                                        data-intervention-id="{{ $intervention->id }}">
                                    Attribuer
                                </button>
                            @elseif ($intervention->status == 'En cours')
                                <button class="cancel-btn px-3 py-1 rounded text-white bg-red-500" 
                                        data-intervention-id="{{ $intervention->id }}">
                                    Annuler
                                </button>
                            @else
                                <button class="px-3 py-1 rounded bg-gray-400 text-white cursor-not-allowed" disabled>
                                    Non fonctionnel
                                </button>
                            @endif
                        </td>
                        <td class="border px-4 py-2">
                            <button onclick="openInterventionDetailsModal('{{ $intervention->id }}', '{{ $intervention->user->name }}', '{{ $intervention->titre }}', '{{ $intervention->description }}', '{{ $intervention->created_at->format('d/m/Y') }}', '{{ $intervention->typeIntervention ? $intervention->typeIntervention->type : 'Non défini' }}', '{{ $intervention->status }}')" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                                Détails
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal pour les détails d'une intervention -->
<div id="interventionDetailsModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-5 rounded-lg shadow-lg w-80 border border-gray-200 relative">
        
        <!-- Bouton de fermeture -->
        <button type="button" onclick="closeInterventionDetailsModal()" 
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-lg">
            ✕
        </button>

        <h2 class="text-lg font-semibold mb-3 text-gray-800 border-b pb-2">Détails de l'intervention</h2>

        <div class="space-y-3 text-sm text-gray-700">
            <div class="flex ">
                <span class="font-medium text-gray-900">ID:</span>
                <span id="detail_intervention_id"></span>
            </div>

            <div class=" ">
                <span class="font-medium text-gray-900">Utilisateur:</span>
                <span id="detail_intervention_user"></span>
            </div>

            <div class="">
                <span class="font-medium text-gray-900">Titre:</span>
                <span id="detail_intervention_titre"></span>
            </div>

            <div class="bg-gray-100 p-3 rounded-lg text-sm text-gray-800">
    <strong>Historique :</strong>
    <p><strong>Date de création :</strong> <span id="detail_intervention_creation_date"></span></p>
    <div>
        <strong>Historique d'attribution :</strong>
        <ul id="detail_intervention_historique" class="list-disc pl-5 text-gray-700"></ul>
    </div>
</div>


            <div class="">
                <span class="font-medium text-gray-900">Type:</span>
                <span id="detail_intervention_type"></span>
            </div>

            <div class=" items-center">
                <span class="font-medium text-gray-900">Statut:</span>
                <span id="detail_intervention_status" class="px-2 py-1    text-xs  font-semibold rounded-md"></span>
            </div>

            <!-- Description avec un cadre arrondi -->
            <div class="bg-gray-100 p-3 rounded-lg text-sm text-gray-800">
                <strong>Description :</strong>
                <p id="detail_intervention_description" class="mt-1"></p>
            </div>
        </div>

        <div class="flex justify-center mt-4">
            <button type="button" onclick="closeInterventionDetailsModal()" 
                    class="px-4 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm transition">
                Fermer
            </button>
        </div>
    </div>
</div>

<script>
    function updateStatusColor(status) {
        const statusElement = document.getElementById("detail_intervention_status");

    
        statusElement.classList.remove("bg-yellow-500", "bg-blue-500", "bg-green-500", "text-white");

        if (status === "En attente") {
            statusElement.classList.add("bg-yellow-500", "text-white");
        } else if (status === "En cours") {
            statusElement.classList.add("bg-blue-500", "text-white");
        } else if (status === "Terminé") {
            statusElement.classList.add("bg-green-500", "text-white");
        }

        statusElement.textContent = status; 
    }
</script>

<script>
   function openInterventionDetailsModal(id, user, titre, description, created_at, type, status) {
    document.getElementById("detail_intervention_id").textContent = id;
    document.getElementById("detail_intervention_user").textContent = user;
    document.getElementById("detail_intervention_titre").textContent = titre;
    document.getElementById("detail_intervention_description").textContent = description;
    document.getElementById("detail_intervention_creation_date").textContent = created_at;
    updateStatusColor(status);

    // Charger l’historique des attributions depuis localStorage
    const historique = JSON.parse(localStorage.getItem('historique_' + id)) || [];
    const historiqueList = document.getElementById("detail_intervention_historique");
    historiqueList.innerHTML = ""; // Vider la liste avant d'ajouter les éléments

    historique.forEach(date => {
        const listItem = document.createElement("li");
        listItem.textContent = date;
        historiqueList.appendChild(listItem);
    });

    document.getElementById("interventionDetailsModal").classList.remove("hidden");
}


    function closeInterventionDetailsModal() {
        document.getElementById('interventionDetailsModal').classList.add('hidden');
    }
</script>




<!-- Modal d’assignation -->
<div id="assignTechnicianModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white p-5 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-3">Attribuer un technicien</h2>
        <form id="assignTechnicianForm" method="POST" action="{{ route('assign.technician') }}">
            @csrf
            @method('PUT')

            <input type="hidden" id="intervention_id" name="intervention_id">

            <!-- Liste déroulante des techniciens -->
            <label for="technicien_id" class="block text-sm font-medium text-gray-700">Sélectionner un technicien :</label>
            <select name="technicien_id" id="technicien_id" class="w-full border rounded p-2 mt-2">
                @foreach($techniciens as $technicien)
                    <option value="{{ $technicien->id }}">{{ $technicien->name }}</option>
                @endforeach
            </select>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                    Confirmer
                </button>
                <button id="closeCancelModal" class="ml-2 bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>



<!-- Modal de confirmation  ALERT -->
<div id="confirmationModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
        <h2 class="text-lg font-semibold mb-4">Êtes-vous sûr de vouloir annuler cette attribution ?</h2>
        <p class="text-gray-600 mb-4">Cette action est irréversible.</p>
        <div class="flex justify-center gap-4">
            <button id="confirmCancel" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                Confirmer
            </button>
            <button id="closeModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                Annuler
            </button>
        </div>
    </div>
</div>
<script>
   document.addEventListener('DOMContentLoaded', function () {
       let currentInterventionId = null;

       // Initialiser DataTables
       $('#interventionsTable').DataTable({
           "language": {
               "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/French.json"
           }
       });

       const modal = document.getElementById('confirmationModal');
       const confirmButton = document.getElementById('confirmCancel');
       const closeButton = document.getElementById('closeModal');

       function attachCancelEvent() {
           document.querySelectorAll('.cancel-btn').forEach(button => {
               button.addEventListener('click', function () {
                   currentInterventionId = this.getAttribute('data-intervention-id');
                   modal.classList.remove('hidden');
               });
           });
       }

       confirmButton.addEventListener('click', function () {
           if (!currentInterventionId) return;

           fetch("{{ route('cancel.technician') }}", {
               method: "PUT",
               headers: {
                   "X-CSRF-TOKEN": "{{ csrf_token() }}",
                   "Content-Type": "application/json"
               },
               body: JSON.stringify({ intervention_id: currentInterventionId })
           })
           .then(response => response.json())
           .then(data => {
               if (data.success) {
                   const row = document.querySelector(`[data-intervention-id="${currentInterventionId}"]`).closest('tr');

                   const statusCell = row.querySelector('.status-cell');
                   if (statusCell) {
                       statusCell.innerHTML = '<span class="px-2 py-1 bg-yellow-500 text-white rounded">En attente</span>';
                   }

                   const actionCell = row.querySelector('.action-cell');
                   if (actionCell) {
                       actionCell.innerHTML = `
                           <button class="assign-btn px-3 py-1 rounded text-white bg-blue-500" 
                                   data-intervention-id="${currentInterventionId}">
                               Attribuer
                           </button>
                       `;
                       attachAssignEvent();
                   }
               }
           })
           .catch(error => console.error('Erreur:', error));

           modal.classList.add('hidden');
       });

       closeButton.addEventListener('click', function () {
           modal.classList.add('hidden');
       });

       function attachAssignEvent() {
           document.querySelectorAll('.assign-btn').forEach(button => {
               button.addEventListener('click', function () {
                   const interventionId = this.getAttribute('data-intervention-id');
                   document.getElementById('intervention_id').value = interventionId;
                   document.getElementById('assignTechnicianModal').classList.remove('hidden');
               });
           });
       }

       attachCancelEvent();
       attachAssignEvent();
   });
</script>







 

@endsection
