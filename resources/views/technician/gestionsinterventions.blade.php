@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md sm:rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-4">Liste d'Interventions</h2>

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
                    <th class="border px-4 py-2">Date de création</th>
                    <th class="border px-4 py-2">Type d'intervention</th>
                    <th class="border px-4 py-2">Statut</th>
                    <th class="border px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($interventions as $intervention)
                    <tr class="border-b border-gray-200 hover:bg-gray-100 transition" data-intervention-id="{{ $intervention->id }}">
                        <td class="border px-4 py-2">{{ $intervention->id }}</td>
                        <td class="border px-4 py-2">{{ $intervention->user->name }}</td>
                        <td class="border px-4 py-2">{{ $intervention->created_at->format('d/m/Y') }}</td>
                        <td class="border px-4 py-2">
                            {{ $intervention->typeIntervention ? $intervention->typeIntervention->type : 'Non défini' }}
                        </td>
                        <td class="border px-4 py-2">
                            @if ($intervention->status === 'Terminé')
                                <span class="px-2 py-1 bg-green-500 text-white rounded">Terminé</span>
                            @elseif ($intervention->status === 'En cours')
                                <span class="px-2 py-1 bg-blue-500 text-white rounded">En cours</span>
                            @else
                                <span class="px-2 py-1 bg-yellow-500 text-white rounded">En attente</span>
                            @endif
                        </td>
                        
                        <td class="border px-4 py-2 text-center space-y-2">

<button onclick="openInterventionDetailsModal('{{ $intervention->id }}', '{{ $intervention->user->name }}', '{{ $intervention->titre }}', '{{ $intervention->description }}', '{{ $intervention->created_at->format('d/m/Y') }}', '{{ $intervention->typeIntervention ? $intervention->typeIntervention->type : 'Non défini' }}', '{{ $intervention->status }}')" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
    Fiche interventions
</button>

@if ($intervention->status !== 'Terminé')
    <button 
        onclick="ouvrirModalTaches({{ $intervention->id }})"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
        id="btn-ajouter-rapport-{{ $intervention->id }}"
    >
        Ajouter détails
    </button>

    <form action="{{ route('intervention.cloturer', $intervention->id) }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition" onclick="return confirm('Voulez-vous vraiment clôturer cette intervention ?')">
            Clôturer
        </button>
    </form>

@else
<form action="{{ route('intervention.reouvrir', $intervention->id) }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition" onclick="return confirm('Voulez-vous vraiment réouvrir cette intervention ?')">
        Réouvrir
    </button>
</form>



@endif

</td>




                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal pour afficher ou ajouter un rapport --}}
<div id="modal-taches" class="hidden fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded shadow-lg w-96 max-h-[90vh] overflow-y-auto">
        <h2 class="text-lg font-semibold mb-4">Détails de l'Intervention</h2>

        {{-- Rapport précédent (lecture seule) --}}
        <div id="rapport-precedent" class="mb-6 p-4 bg-gray-100 border rounded hidden">
            <h3 class="text-md font-semibold mb-2 text-gray-700">Rapport précédent</h3>
            <p id="ancien-contenu" class="text-sm text-gray-800 whitespace-pre-wrap"></p>
            <p id="ancienne-date" class="text-xs text-gray-500 mt-2 italic"></p>
        </div>

        <form id="formRapport">
            <input type="hidden" id="intervention_id">

            <label for="contenu">Problème posé :</label>
            <textarea id="contenu" class="w-full p-2 border rounded mb-4" required></textarea>

            <label for="nouvelle-tache">Ajouter une tâche :</label>
            <div class="flex gap-2">
                <input type="text" id="nouvelle-tache" class="w-full p-2 border rounded mb-2">
                <button type="button" onclick="ajouterTache()" class="bg-blue-500 text-white px-3 rounded">Ajouter</button>
            </div>

            <div id="taches-container" class="mt-4 space-y-2"></div>

            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="fermerModalTaches()" class="bg-gray-300 px-4 py-2 rounded">Fermer</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Enregistrer</button>
            </div>
        </form>
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
    <!-- ID de l'intervention -->
    <div class="grid grid-cols-4 gap-2">
        <div class="col-span-1">
            <span class="font-medium text-gray-900">ID:</span>
        </div>
        <div class="col-span-3">
            <span id="detail_intervention_id">{{ $intervention->id }}</span>
        </div>
    </div>

    <!-- Utilisateur -->
    <div class="grid grid-cols-4 gap-2">
        <div class="col-span-1">
            <span class="font-medium text-gray-900">Utilisateur:</span>
        </div>
        <div class="col-span-3">
            <span id="detail_intervention_user">{{ $intervention->user->name }}</span>
        </div>
    </div>

    <!-- Titre de l'intervention -->
    <div class="grid grid-cols-4 gap-2">
        <div class="col-span-1">
            <span class="font-medium text-gray-900">Titre:</span>
        </div>
        <div class="col-span-3">
            <span id="detail_intervention_titre">{{ $intervention->titre }}</span>
        </div>
    </div>

    <!-- Date de création -->
    <div class="grid grid-cols-4 gap-2">
        <div class="col-span-1">
            <span class="font-medium text-gray-900">Date de création:</span>
        </div>
        <div class="col-span-3">
            <span id="detail_intervention_creation_date">{{ $intervention->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <!-- Type d'intervention -->
    <div class="grid grid-cols-4 gap-2">
        <div class="col-span-1">
            <span class="font-medium text-gray-900">Type:</span>
        </div>
        <div class="col-span-3">
            <span id="detail_intervention_type">{{ $intervention->type }}</span>
        </div>
    </div>

    <!-- Statut de l'intervention -->
    <div class="grid grid-cols-4 gap-2">
        <div class="col-span-1">
            <span class="font-medium text-gray-900">Statut:</span>
        </div>
        <div class="col-span-3">
            <span id="detail_intervention_status" class="px-3 py-1 text-sm font-semibold rounded-md {{ $intervention->status == 'terminée' ? 'bg-green-200' : 'bg-yellow-200' }}">
                {{ ucfirst($intervention->status) }}
            </span>
        </div>
    </div>

    <!-- Description de l'intervention -->
    <div class="bg-gray-50 p-4 rounded-lg text-gray-800 border border-gray-200">
        <strong class="block mb-2 text-gray-900">Description :</strong>
        <p id="detail_intervention_description" class="mt-2 text-gray-700 whitespace-pre-line">{{ $intervention->description }}</p>
    </div>

    <!-- Affichage de la date de réouverture si disponible -->
    @if(isset($lastReouverture))
        <div class="mt-4">
            <strong class="block text-gray-900">Réouverture :</strong>
            <span class="text-sm text-gray-700">{{ $lastReouverture->created_at->format('d/m/Y H:i') }}</span>
        </div>
    @endif
</div>
<!-- Section des détails du rapport (visible seulement si l'intervention est terminée) -->
<div id="rapportSection" class="mt-6 space-y-4 {{ $intervention->status == 'terminée' ? '' : 'hidden' }} border-t pt-4">
    <h3 class="font-semibold text-lg text-gray-800 mb-3">Détails du rapport</h3>

    <div class="space-y-3 text-sm text-gray-700">
        <div class="grid grid-cols-4 gap-2">
            <div class="col-span-1"><strong>ID Rapport:</strong></div>
            <div class="col-span-3"><span id="rapportId">{{ $intervention->rapport->id ?? 'N/A' }}</span></div>
        </div>
        
        <div class="grid grid-cols-4 gap-2">
            <div class="col-span-1"><strong>Intervention:</strong></div>
            <div class="col-span-3"><span id="interventionId">{{ $intervention->id }}</span></div>
        </div>
        
        <div class="grid grid-cols-4 gap-2">
    <div class="col-span-1"><strong>Date de traitement:</strong></div>
    <div class="col-span-3">
        @if($intervention->rapport->date_traitement)
            <span id="dateTraitement">{{ $intervention->rapport->date_traitement->format('d/m/Y H:i') }}</span>
        @else
            <span id="dateTraitement">Non renseignée</span>
        @endif
    </div>
</div>

        
        <div class="grid grid-cols-4 gap-2">
            <div class="col-span-1"><strong>Technicien:</strong></div>
            <div class="col-span-3"><span id="technicienNom">{{ $intervention->rapport->technicien->name ?? 'N/A' }}</span></div>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <strong class="block mb-2 text-gray-900">Contenu du rapport :</strong>
            <p id="rapportContenu" class="mt-2 text-gray-700 whitespace-pre-line">{{ $intervention->rapport->contenu ?? 'Aucun rapport ajouté' }}</p>
        </div>
        
        <div class="mt-3">
            <strong class="block mb-2 text-gray-900">Tâches effectuées :</strong>
            <ul id="rapportTaches" class="space-y-2">
                @foreach($intervention->rapport->taches as $tache)
                    <li class="text-sm text-gray-700">{{ $tache->description }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Affichage de la réouverture si l'intervention a été réouverte -->
        @if(isset($lastReouverture))
            <div class="mt-4">
                <strong class="block text-gray-900">Réouverture :</strong>
                <span class="text-sm text-gray-700">{{ $lastReouverture->created_at->format('d/m/Y H:i') }}</span>

                @if($intervention->rapport && $intervention->rapport->updated_at > $lastReouverture->created_at)
                    <!-- Si un rapport a été ajouté après la réouverture, on affiche le rapport comme d'habitude -->
                    <div class="mt-3">
                        <strong class="block text-gray-900">Nouveau Rapport :</strong>
                        <p class="text-sm text-gray-700">{{ $intervention->rapport->contenu }}</p>
                    </div>
                @else
                    <!-- Si aucune modification n'a été faite, on affiche juste la date -->
                    <p class="text-sm text-gray-700">Aucune modification après la réouverture.</p>
                @endif
            </div>
        @endif
    </div>
</div>


<!-- Boutons pour fermer ou imprimer -->
<div class="flex justify-center mt-6">
    <button type="button" onclick="closeInterventionDetailsModal()" 
            class="px-5 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm transition">
        Fermer
    </button>
    
    <!-- Bouton Imprimer -->
    <button type="button" onclick="printIntervention()" 
            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition mr-2">
        <i class="fas fa-print mr-1"></i> Imprimer  
    </button>  
</div>


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

    function remplirRapportPrecedent(contenu, updatedAt) {
    const rapportDiv = document.getElementById("rapport-precedent");

    if (contenu) {
        document.getElementById("ancien-contenu").textContent = contenu;
        document.getElementById("ancienne-date").textContent = `Dernière modification : ${updatedAt}`;
        rapportDiv.classList.remove("hidden");
    } else {
        rapportDiv.classList.add("hidden");
    }
}


    
</script>










<script>
    let interventionIdSelected = null;
    let taches = [];

    function ouvrirModalTaches(interventionId) {
        interventionIdSelected = interventionId;
        document.getElementById('intervention_id').value = interventionId;
        document.getElementById('contenu').value = '';
        taches = [];
        document.getElementById('taches-container').innerHTML = '';

        fetch(`/intervention/${interventionId}/rapport`)
            .then(response => response.json())
            .then(data => {
                console.log('Données reçues:', data); 
                if (!data.error) { 
                    if (data.rapport_id) { 
                        document.getElementById('contenu').value = data.contenu || '';
                    }

                  
                    if (data.taches && data.taches.length > 0) {
                        taches = Array.isArray(data.taches) ? data.taches : [];
                        taches.forEach(desc => ajouterTacheHTML(desc));
                    }
                }
                document.getElementById('modal-taches').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Erreur lors de la récupération du rapport:', error);
                document.getElementById('modal-taches').classList.remove('hidden');
            });
    }

    function fermerModalTaches() {
        document.getElementById('modal-taches').classList.add('hidden');
    }

    function ajouterTache() {
        const input = document.getElementById('nouvelle-tache');
        const description = input.value.trim();
        if (description && !taches.includes(description)) {
            taches.push(description);
            ajouterTacheHTML(description);
            input.value = '';
        }
    }

    function ajouterTacheHTML(description) {
        const container = document.getElementById('taches-container');
        const div = document.createElement('div');
        div.className = "flex justify-between items-center bg-gray-100 p-2 rounded";
        div.innerHTML = `
            <span>${description}</span>
            <button type="button" onclick="supprimerTache('${description}')" class="text-red-500 hover:text-red-700">Supprimer</button>
        `;
        container.appendChild(div);
    }

    function supprimerTache(description) {
        taches = taches.filter(t => t !== description);
        document.getElementById('taches-container').innerHTML = '';
        taches.forEach(ajouterTacheHTML);
    }

    document.getElementById('formRapport').addEventListener('submit', function (e) {
        e.preventDefault();

        const contenu = document.getElementById('contenu').value.trim();

        fetch("{{ route('rapports.storeOrUpdate') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                intervention_id: interventionIdSelected,
                contenu: contenu,
                taches: taches
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                fermerModalTaches();

                const row = document.querySelector(`tr[data-intervention-id="${interventionIdSelected}"]`);
                const statusCell = row.querySelector('td:nth-child(5)');
                statusCell.innerHTML = '<span class="px-2 py-1 bg-green-500 text-white rounded">Terminé</span>';

                const addButton = row.querySelector('button');
                if (addButton) {
                    addButton.innerHTML = 'Voir Rapport';
                }

                const form = row.querySelector('form');
                if (form) form.remove();
            } else {
                alert('Erreur : ' + (data.message || 'Erreur lors de l\'enregistrement du rapport'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de l\'enregistrement du rapport');
        });
    });
</script>

@endsection
