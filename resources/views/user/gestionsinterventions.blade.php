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
                                <a href="{{ route('interventions.show', $intervention->id) }}" class="btn btn-sm btn-info">
    <i class="fas fa-eye"></i> Détails
</a>
                                    
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