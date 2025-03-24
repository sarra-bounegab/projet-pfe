@extends('layouts.user')

@section('content')
    <div class="py-6 h-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Gestion des Interventions</h2>

                <!-- Table des interventions -->
                <table id="interventionsTable" class="min-w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">#</th>
                            <th class="px-4 py-2 border">Titre</th>
                            <th class="px-4 py-2 border">Description</th>
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
                                <td class="border px-4 py-2">{{ $intervention->description }}</td>
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
                                <td class="border px-4 py-2">
                                    @if($intervention->status == 'Terminé')
                                        <button onclick="ouvrirModalRapport({{ $intervention->id }})" 
                                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                                            Voir Rapport
                                        </button>
                                    @else
                                        <span class="text-gray-400">Non disponible</span>
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

    <!-- Modal de rapport -->
    <div id="rapportModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
            <div class="flex justify-between items-center border-b pb-4 mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Détails du Rapport</h2>
                <button onclick="fermerModalRapport()" class="text-gray-500 hover:text-gray-700 transition">
                    ✖
                </button>
            </div>
            <div class="space-y-4 text-sm text-gray-700">
                <div><strong>ID Rapport:</strong> <span id="rapportId"></span></div>
                <div><strong>Intervention:</strong> <span id="interventionId"></span></div>
                <div><strong>Date de traitement:</strong> <span id="dateTraitement"></span></div>
                <div><strong>Technicien:</strong> <span id="technicienNom" class="text-black-600"></span></div>
                <div class="bg-gray-100 p-3 rounded-md">
                    <strong>Contenu :</strong>
                    <p id="rapportContenu" class="mt-1"></p>
                </div>
                <div>
                    <strong>Tâches associées :</strong>
                    <ul id="rapportTaches" class="mt-2 space-y-2"></ul>
                </div>
            </div>
            <div class="mt-6 flex justify-center">
                <button onclick="fermerModalRapport()"
                    class="bg-gray-700 hover:bg-gray-600 text-white px-5 py-2 rounded-md transition">
                    Fermer
                </button>
            </div>
        </div>
    </div>

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

        function ouvrirModalRapport(interventionId) {
            fetch("{{ url('/intervention') }}/" + interventionId + "/rapport")
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        document.getElementById('rapportId').textContent = data.rapport_id;
                        document.getElementById('interventionId').textContent = data.intervention_id;
                        document.getElementById('dateTraitement').textContent = data.date_traitement;
                        document.getElementById('technicienNom').textContent = data.technicien_nom;
                        document.getElementById('rapportContenu').textContent = data.contenu;
                        let listeTaches = document.getElementById('rapportTaches');
                        listeTaches.innerHTML = '';
                        if (data.taches && data.taches.length > 0) {
                            data.taches.forEach(tache => {
                                let li = document.createElement('li');
                                li.className = "bg-green-100 px-3 py-2 rounded-md text-black-800 font-medium";
                                li.textContent = tache;
                                listeTaches.appendChild(li);
                            });
                        } else {
                            listeTaches.innerHTML = "<li class='text-gray-500 italic'>Aucune tâche</li>";
                        }
                        document.getElementById('rapportModal').classList.remove('hidden');
                    }
                });
        }

        function fermerModalRapport() {
            document.getElementById('rapportModal').classList.add('hidden');
        }
    </script>
@endsection

