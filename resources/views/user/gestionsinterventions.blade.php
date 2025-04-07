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
                                
                         <td class="border px-4 py-2">
                            <button onclick="openInterventionDetailsModal('{{ $intervention->id }}', '{{ $intervention->user->name }}', '{{ $intervention->titre }}', '{{ $intervention->description }}', '{{ $intervention->created_at->format('d/m/Y') }}', '{{ $intervention->typeIntervention ? $intervention->typeIntervention->type : 'Non défini' }}', '{{ $intervention->status }}')" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                                Détails
                            </button>
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

            <!-- Section des détails du rapport (visible seulement si l'intervention est terminée) -->
            <div id="rapportSection" class="mt-6 space-y-4 hidden border-t pt-4">
                <h3 class="font-semibold text-lg text-gray-800 mb-3"></h3>

                <div class="space-y-3 text-sm text-gray-700">
                    <div class="grid grid-cols-4 gap-2">
                        <div class="col-span-1"><strong>ID Rapport:</strong></div>
                        <div class="col-span-3"><span id="rapportId"></span></div>
                    </div>
                    
                    <div class="grid grid-cols-4 gap-2">
                        <div class="col-span-1"><strong>Intervention:</strong></div>
                        <div class="col-span-3"><span id="interventionId"></span></div>
                    </div>
                    
                    <div class="grid grid-cols-4 gap-2">
                        <div class="col-span-1"><strong>Date de traitement:</strong></div>
                        <div class="col-span-3"><span id="dateTraitement"></span></div>
                    </div>
                    
                    <div class="grid grid-cols-4 gap-2">
                        <div class="col-span-1"><strong>Technicien:</strong></div>
                        <div class="col-span-3"><span id="technicienNom"></span></div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <strong class="block mb-2 text-gray-900">Contenu du rapport :</strong>
                        <p id="rapportContenu" class="mt-2 text-gray-700 whitespace-pre-line"></p>
                    </div>
                    
                    <div class="mt-3">
                        <strong class="block mb-2 text-gray-900">Tâches effectuées :</strong>
                        <ul id="rapportTaches" class="space-y-2"></ul>
                    </div>
                    
                </div>
            </div>

            <div class="flex justify-center mt-6">
                <button type="button" onclick="closeInterventionDetailsModal()" 
                        class="px-5 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm transition">
                    Fermer
                </button>
                <!-- Bouton -->
<button type="button" onclick="printIntervention()" 
        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition mr-2">
    <i class="fas fa-print mr-1"></i> Imprimer  
</button>

<!-- Script -->
<script>
    function printIntervention() {
    // Récupération des données de l'intervention
    const id = document.getElementById('detail_intervention_id').textContent.trim();
    const user = document.getElementById('detail_intervention_user').textContent.trim();
    const titre = document.getElementById('detail_intervention_titre').textContent.trim();
    const description = document.getElementById('detail_intervention_description').textContent.trim();
    const date = document.getElementById('detail_intervention_creation_date').textContent.trim();
    const type = document.getElementById('detail_intervention_type').textContent.trim();
    const status = document.getElementById('detail_intervention_status').textContent.trim().toLowerCase();

    // Vérifier si le statut est "terminé"
    if (status !== "terminé") {
        alert("L'impression est uniquement possible lorsque l'intervention est terminée.");
        return;
    }

    // Récupération des données du rapport
    const rapportId = document.getElementById('rapportId')?.textContent.trim() || "N/A";
    const technicienNom = document.getElementById('technicienNom')?.textContent.trim() || "N/A";
    const dateTraitement = document.getElementById('dateTraitement')?.textContent.trim() || "N/A";
    const rapportContenu = document.getElementById('rapportContenu')?.textContent.trim() || "Aucun contenu disponible";
    
    // Récupération des tâches
    const tachesElements = document.getElementById('rapportTaches')?.querySelectorAll('li') || [];
    let tachesHTML = '';
    
    if (tachesElements.length > 0) {
        tachesHTML = '<ul class="taches-list">';
        tachesElements.forEach(tache => {
            if (!tache.classList.contains('text-gray-500')) {
                tachesHTML += `<li>${tache.textContent}</li>`;
            }
        });
        tachesHTML += '</ul>';
    } else {
        tachesHTML = '<p class="empty-taches">Aucune tâche spécifique enregistrée</p>';
    }

    // Créer la fenêtre d'impression
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Rapport d'intervention #${id}</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 15px; 
                    line-height: 1.4;
                    color: #333;
                    font-size: 12px;
                }
                .header { 
                    text-align: center;
                    margin-bottom: 15px;
                    border-bottom: 1px solid #2563eb;
                    padding-bottom: 10px;
                }
                .logo { 
                    max-width: 100px; 
                    margin-bottom: 5px; 
                }
                h1 { 
                    font-size: 18px; 
                    margin: 5px 0;
                    color: #1e40af;
                }
                .document-date {
                    font-size: 12px;
                    color: #666;
                    margin: 2px 0;
                }
                .card {
                    background-color: #f8fafc;
                    border: 1px solid #e2e8f0;
                    border-radius: 5px;
                    padding: 10px;
                    margin-bottom: 15px;
                }
                h2 {
                    font-size: 14px;
                    color: #000000;
                    margin: 0 0 8px 0;
                    padding-bottom: 5px;
                    border-bottom: 1px solid #e2e8f0;
                }
                .info-grid {
                    display: grid;
                    grid-template-columns: 100px 1fr;
                    row-gap: 5px;
                }
                .label {
                    font-weight: bold;
                    color: #4b5563;
                }
                .value {
                    color: #1f2937;
                }
                .description-box, .rapport-content {
                    background-color: #ffffff;
                    border: 1px solid #e5e7eb;
                    padding: 8px;
                    border-radius: 4px;
                    margin-top: 8px;
                    font-size: 11px;
                }
                .status-badge {
                    display: inline-block;
                    padding: 2px 8px;
                    border-radius: 10px;
                    background-color: #10b981;
                    color: white;
                    font-weight: 600;
                    font-size: 11px;
                }
                .taches-list {
                    list-style-type: none;
                    padding-left: 0;
                    margin: 5px 0;
                }
                .taches-list li {
                    background-color: #ecfdf5;
                    padding: 4px 8px;
                    margin-bottom: 4px;
                    border-radius: 3px;
                    border-left: 3px solid #000000;
                    font-size: 11px;
                }
                .empty-taches {
                    color: #6b7280;
                    font-style: italic;
                    margin: 5px 0;
                }
                .signature-container { 
                    margin-top: 15px; 
                    display: grid; 
                    grid-template-columns: 1fr 1fr; 
                    gap: 20px;
                }
                .signature-box { 
                    text-align: center; 
                }
                .signature-line { 
                    margin-top: 40px; 
                    border-top: 1px solid #000; 
                    width: 100%; 
                }
                .signature-name {
                    margin-top: 5px;
                    font-size: 11px;
                }
                .footer { 
                    text-align: center; 
                    margin-top: 15px; 
                    padding-top: 5px;
                    border-top: 1px solid #e5e7eb;
                    font-size: 10px; 
                    color: #6b7280; 
                }
                p {
                    margin: 5px 0;
                }
                @media print {
                    body {
                        margin: 0;
                        padding: 10px;
                    }
                    .card {
                        break-inside: avoid;
                    }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <images/Logo-Anep-Animation.png" alt="ANEP Algérie" class="logo">
                <h1>Détails de l'intervention #${id}</h1>
                <p class="document-date">Document généré le ${new Date().toLocaleDateString('fr-FR')}</p>
            </div>

            <div class="card">
                <h2>Informations de l'intervention</h2>
                <div class="info-grid">
                    <div class="label">ID :</div>
                    <div class="value">${id}</div>

                    <div class="label">Utilisateur :</div>
                    <div class="value">${user}</div>

                    <div class="label">Titre :</div>
                    <div class="value">${titre}</div>

                    <div class="label">Date de création :</div>
                    <div class="value">${date}</div>

                    <div class="label">Type :</div>
                    <div class="value">${type}</div>

                    <div class="label">Statut :</div>
                    <div class="value"><span class="status-badge">Terminé</span></div>
                </div>
                
                <div class="description-box">
                    <div class="label">Description :</div>
                    <p>${description}</p>
                </div>
            </div>

            <div class="card">
             <images/Logo-Anep-Animation.png" alt="ANEP Algérie" class="logo">
                <h2>Détails de l'intervention</h2>
                <div class="info-grid">
                    <div class="label">ID Rapport :</div>
                    <div class="value">${rapportId}</div>

                    <div class="label">Technicien :</div>
                    <div class="value">${technicienNom}</div>

                    <div class="label">Date traitement :</div>
                    <div class="value">${dateTraitement}</div>
                </div>
                
                <div class="rapport-content">
                    <div class="label"> Probleme posé :</div>
                    <p>${rapportContenu}</p>
                </div>
                
                <div style="margin-top: 8px;">
                    <div class="label">Tâches effectuées :</div>
                    ${tachesHTML}
                </div>
            </div>

            <div class="signature-container">
                <div class="signature-box">
                    <p>Signature du technicien</p>
                    <div class="signature-line"></div>
                    <p class="signature-name">${technicienNom}</p>
                </div>
                <div class="signature-box">
                    <p>Signature du responsable</p>
                    <div class="signature-line"></div>
                    <p class="signature-name">___________________</p>
                </div>
            </div>

            <div class="footer">
                <p>ANEP Algérie - Entreprise Nationale de Communication, d’Édition et de Publicité</p>
            </div>
        </body>
        </html>
    `);

    // Impression après chargement
    printWindow.document.close();
    printWindow.onload = function () {
        printWindow.focus();
        printWindow.print();
    };
}
</script>



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

        // Si l'intervention est terminée, afficher la section rapport
        if (status === "Terminé") {
            document.getElementById('rapportSection').classList.remove('hidden');
            // Récupérer et afficher les détails du rapport
            fetch("{{ url('/intervention') }}/" + id + "/rapport")
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('rapportSection').innerHTML = `
                            <div class="p-4 bg-yellow-50 text-yellow-800 rounded-lg border border-yellow-200">
                                <p class="font-medium">Aucun rapport disponible</p>
                                <p class="text-sm">Ce ticket est marqué comme terminé mais aucun rapport n'a été enregistré.</p>
                            </div>
                        `;
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
                                li.className = "bg-green-50 px-4 py-2 rounded-md text-green-800 font-medium border border-green-200";
                                li.textContent = tache;
                                listeTaches.appendChild(li);
                            });
                        } else {
                            listeTaches.innerHTML = "<li class='text-gray-500 italic py-2'>Aucune tâche associée</li>";
                        }
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    document.getElementById('rapportSection').innerHTML = `
                        <div class="p-4 bg-red-50 text-red-800 rounded-lg border border-red-200">
                            <p class="font-medium">Erreur lors du chargement du rapport</p>
                            <p class="text-sm">Impossible de récupérer les informations du rapport.</p>
                        </div>
                    `;
                });
        } else {
            document.getElementById('rapportSection').classList.add('hidden');
        }

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

        function ouvrirModalRapport(interventionId) {
    fetch("{{ url('/intervention') }}/" + interventionId + "/rapport")
        .then(response => response.json())
        .then(data => {
            console.log(data);  // Ajoutez ceci pour voir ce qui est renvoyé par l'API

            if (data.error) {
                alert(data.error);
            } else {
                // Remplir les détails du rapport
                document.getElementById('rapportId').textContent = data.rapport_id;
                document.getElementById('interventionId').textContent = data.intervention_id;
                document.getElementById('dateTraitement').textContent = data.date_traitement;
                document.getElementById('technicienNom').textContent = data.technicien_nom;
                document.getElementById('rapportContenu').textContent = data.contenu;

                // Remplir les détails de l'intervention
                document.getElementById('interventionTitre').textContent = data.titre;

                // Ajout du type d'intervention
                let interventionType = '';
                if (data.type_intervention_id == 2) {
                    interventionType = 'Matériel';
                } else if (data.type_intervention_id == 1) {
                    interventionType = 'Logiciel';
                } else {
                    interventionType = 'Inconnu';
                }
                document.getElementById('interventionType').textContent = interventionType;

                // Statut de l'intervention
                let interventionStatus = '';
                if (data.status == 'En attente') {
                    interventionStatus = 'En attente';
                } else if (data.status == 'En cours') {
                    interventionStatus = 'En cours';
                } else if (data.status == 'Terminé') {
                    interventionStatus = 'Terminé';
                }
                document.getElementById('interventionStatut').textContent = interventionStatus;

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

