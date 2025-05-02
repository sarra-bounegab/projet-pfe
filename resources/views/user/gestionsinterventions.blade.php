@extends('layouts.user')

@section('content')
    <div class="py-6 h-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Gestion des Interventions</h2>
                <a href="{{ route('user.gestionsinterventions.create') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                        <i class="fa-solid fa-circle-plus" ></i>
                        Ajouter Intervention
                        </a>

                <!-- Table des interventions -->
                <table id="interventionsTable" class="min-w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">#</th>
                            <th class="px-4 py-2 border">Titre</th>
                            <th class="px-4 py-2 border">Type</th>
                            <th class="px-4 py-2 border">Statut</th>
                            <th class="px-4 py-2 border">Créé le</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interventions as $intervention)
                            <tr class="text-center hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2">{{ $intervention->titre }}</td>
                                
                                <td class="border px-4 py-2">
                                    @if ($intervention->type_intervention_id == 2) Matériel
                                    @elseif ($intervention->type_intervention_id == 1) Logiciel
                                    @else Inconnu @endif
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
                                <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($intervention->created_at)->format('d/m/Y H:i') }}</td>
                                
                                <td class="border px-4 py-2 space-x-2">
                                    <button onclick="openInterventionDetailsModal('{{ $intervention->id }}', '{{ $intervention->user->name }}', '{{ $intervention->titre }}', '{{ $intervention->description }}', '{{ $intervention->created_at->format('d/m/Y') }}', '{{ $intervention->typeIntervention ? $intervention->typeIntervention->type : 'Non défini' }}', '{{ $intervention->status }}')" 
                                            class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                                        Détails
                                    </button>
                                    
                                    @if(in_array($intervention->status, ['En attente']))
                                        <!-- Bouton Modifier -->
                                        <button onclick="openEditModal('{{ $intervention->id }}', '{{ $intervention->titre }}', '{{ $intervention->description }}')" 
                                                class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            Modifier
                                        </button>
                                    @endif
                                    
                                    @if($intervention->status == 'En attente')
                                        <!-- Bouton Supprimer -->
                                        <form action="{{ route('interventions.destroy_user', $intervention->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette intervention ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Aucune intervention trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal pour modifier l'intervention -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Modifier l'intervention</h3>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mt-2 px-7 py-3 text-left">
                        <div class="mb-4">
                            <label for="edit_titre" class="block text-sm font-medium text-gray-700">Titre</label>
                            <input type="text" id="edit_titre" name="titre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div class="mb-4">
                            <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="edit_description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                    </div>
                    <div class="px-4 py-3 flex justify-end">
                        <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="mr-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, titre, description) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('edit_titre').value = titre;
            document.getElementById('edit_description').value = description;
            
            // Mettre à jour l'action du formulaire
            const form = document.getElementById('editForm');
            form.action = `/interventions/${id}/update_user`;
        }

        // Fermer le modal si on clique en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.classList.add('hidden');
            }
        }
        function confirmDelete(interventionId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette intervention ?')) {
        document.getElementById('delete-form-' + interventionId).submit();
    }
}
    </script>

    
<!-- Modal pour les détails de l'intervention -->
<div class="h-screen flex flex-col items-center justify-center bg-gray-50 py-">
    <div id="interventionDetailsModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-2xl border border-gray-200 relative max-h-[90vh] overflow-y-auto">
            
            <!-- Bouton de fermeture -->
            <button type="button" onclick="closeInterventionDetailsModal()" 
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>

            <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Détails de l'intervention</h2>

            <!-- Section des détails de l'intervention -->
            <div class="space-y-4 text-sm text-gray-700">
                <div class="grid grid-cols-4 gap-2">
                    <div class="col-span-1">
                        <span class="font-medium text-gray-900">ID:</span>
                    </div>
                    <div class="col-span-3">
                        <span id="detail_intervention_id"></span>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-2">
                    <div class="col-span-1">
                        <span class="font-medium text-gray-900">Utilisateur:</span>
                    </div>
                    <div class="col-span-3">
                        <span id="detail_intervention_user"></span>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-2">
                    <div class="col-span-1">
                        <span class="font-medium text-gray-900">Titre:</span>
                    </div>
                    <div class="col-span-3">
                        <span id="detail_intervention_titre"></span>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-2">
                    <div class="col-span-1">
                        <span class="font-medium text-gray-900">Date de création:</span>
                    </div>
                    <div class="col-span-3">
                        <span id="detail_intervention_creation_date"></span>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-2">
                    <div class="col-span-1">
                        <span class="font-medium text-gray-900">Type:</span>
                    </div>
                    <div class="col-span-3">
                        <span id="detail_intervention_type"></span>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-2">
                    <div class="col-span-1">
                        <span class="font-medium text-gray-900">Statut:</span>
                    </div>
                    <div class="col-span-3">
                        <span id="detail_intervention_status" class="px-3 py-1 text-sm font-semibold rounded-md"></span>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg text-gray-800 border border-gray-200">
                    <strong class="block mb-2 text-gray-900">Description :</strong>
                    <p id="detail_intervention_description" class="mt-2 text-gray-700 whitespace-pre-line"></p>
                </div>
            </div>

            <!-- Section technique -->
<div class="border-t pt-4 mt-4 space-y-4 text-sm text-gray-700">
    <h3 class="text-lg font-semibold text-gray-800">Informations techniques</h3>

    <!-- Technicien -->
    <div class="grid grid-cols-4 gap-2">
        <div class="col-span-1">
            <span class="font-medium text-gray-900">Technicien:</span>
        </div>
        <div class="col-span-3">
            <span id="detail_intervention_technicien"></span>
        </div>
    </div>

    <!-- Type d'intervention -->
    <div class="grid grid-cols-4 gap-2">
        <div class="col-span-1">
            <span class="font-medium text-gray-900">Type d'intervention:</span>
        </div>
        <div class="col-span-3">
            <span id="detail_intervention_type_intervention"></span>
        </div>
    </div>

    <!-- Contenu -->
    <div class="bg-gray-50 p-4 rounded-lg text-gray-800 border border-gray-200">
        <strong class="block mb-2 text-gray-900">Contenu :</strong>
        <p id="detail_intervention_contenu" class="mt-2 text-gray-700 whitespace-pre-line"></p>
    </div>
</div>


            <div class="flex justify-center mt-6">
                <button type="button" onclick="closeInterventionDetailsModal()" 
                        class="px-5 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm transition">
                    Fermer
                </button>
            </div>
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

    function openInterventionDetailsModal(id, user, titre, description, created_at, type, status) {
        // Mettre à jour les détails de l'intervention
        document.getElementById("detail_intervention_id").textContent = id;
        document.getElementById("detail_intervention_user").textContent = user;
        document.getElementById("detail_intervention_titre").textContent = titre;
        document.getElementById("detail_intervention_description").textContent = description;
        document.getElementById("detail_intervention_creation_date").textContent = created_at;
        document.getElementById("detail_intervention_type").textContent = type;
        updateStatusColor(status);

        // Afficher le modal
        document.getElementById("interventionDetailsModal").classList.remove("hidden");
    }

    function closeInterventionDetailsModal() {
        document.getElementById('interventionDetailsModal').classList.add('hidden');
    }
</script>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#interventionsTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/French.json"
                },
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "responsive": true,
                "pageLength": 8
            });
        });
    </script>
@endsection