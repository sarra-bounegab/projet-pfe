@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md sm:rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-4">Liste d'Interventions</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($interventions as $intervention)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $intervention->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $intervention->user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $intervention->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($intervention->status === 'Terminé')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Terminé</span>
                        @elseif ($intervention->status === 'En cours')
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">En cours</span>
                        @else
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">En attente</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                        <button onclick="openInterventionDetailsModal('{{ $intervention->id }}', '{{ $intervention->user->name }}', '{{ $intervention->titre }}', '{{ $intervention->description }}', '{{ $intervention->created_at->format('d/m/Y') }}', '{{ json_encode($intervention->typesIntervention) }}', '{{ $intervention->status }}')" 
                                class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 text-sm">
                            Details 
                        </button>
                        

                        @if ($intervention->status !== 'Terminé')
                        <button type="button" class="btn btn-primary" data-modal-target="remplirContenuModal" data-intervention-id="{{ $intervention->id }}">
                        <i class="fa-solid fa-circle-plus" ></i> ajouter details techniques 
                        </button>

                        <form action="{{ route('intervention.cloturer', $intervention->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm"
                                    onclick="return confirm('Voulez-vous vraiment clôturer cette intervention ?')">
                                Clôturer
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

<!-- Modal pour remplir le contenu -->
<div id="remplirContenuModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-2xl border border-gray-200 relative max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4 border-b pb-3">
            <h5 class="text-lg font-semibold" id="remplirContenuModalLabel">Détails de l'intervention</h5>
            <button type="button" class="text-gray-500 hover:text-gray-700 close-modal">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="modal-body">
            <input type="hidden" id="modalInterventionId">

            <!-- Liste des détails existants -->
            <div class="mb-4">
                <h6 class="text-sm font-medium text-gray-700 mb-2">Types d'interventions existants</h6>
                <div id="detailsList" class="space-y-3 mb-4">
                    <!-- Les détails seront chargés ici dynamiquement -->
                </div>
            </div>

            <!-- Formulaire pour ajouter un nouveau détail -->
            <form id="contenuForm" class="mt-4">
                <h6 class="text-sm font-medium text-gray-700 mb-2">Ajouter un nouveau type d'intervention</h6>
                <div class="mb-3">
                    <label for="type_intervention_id" class="block text-sm font-medium text-gray-700 mb-1">Type d'intervention</label>
                    <select class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" id="type_intervention_id" name="type_intervention_id" required>
                        <option value="">Sélectionner un type</option>
                        @foreach($typesIntervention as $type)
                            <option value="{{ $type->id }}">{{ $type->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="contenu" class="block text-sm font-medium text-gray-700 mb-1">Description détaillée</label>
                    <textarea class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" id="contenu" name="contenu" rows="5" required></textarea>
                </div>
                <div class="flex justify-end mt-4 space-x-2">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 close-modal">Fermer</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal pour les détails de l'intervention --}}
<div id="interventionDetailsModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-2xl border border-gray-200 relative max-h-[90vh] overflow-y-auto">
        
        {{-- Bouton de fermeture --}}
        <button type="button" onclick="closeInterventionDetailsModal()" 
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>

        <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Détails de l'intervention</h2>

        {{-- Section des détails de l'intervention --}}
        <div class="space-y-4 text-sm text-gray-700">
            {{-- ID de l'intervention --}}
            <div class="grid grid-cols-4 gap-2">
                <div class="col-span-1">
                    <span class="font-medium text-gray-900">ID:</span>
                </div>
                <div class="col-span-3">
                    <span id="detail_intervention_id"></span>
                </div>
            </div>

            {{-- Utilisateur --}}
            <div class="grid grid-cols-4 gap-2">
                <div class="col-span-1">
                    <span class="font-medium text-gray-900">Utilisateur:</span>
                </div>
                <div class="col-span-3">
                    <span id="detail_intervention_user"></span>
                </div>
            </div>

            {{-- Titre de l'intervention --}}
            <div class="grid grid-cols-4 gap-2">
                <div class="col-span-1">
                    <span class="font-medium text-gray-900">Titre:</span>
                </div>
                <div class="col-span-3">
                    <span id="detail_intervention_titre"></span>
                </div>
            </div>

            {{-- Date de création --}}
            <div class="grid grid-cols-4 gap-2">
                <div class="col-span-1">
                    <span class="font-medium text-gray-900">Date de création:</span>
                </div>
                <div class="col-span-3">
                    <span id="detail_intervention_creation_date"></span>
                </div>
            </div>

            {{-- Type d'intervention --}}
            <div class="grid grid-cols-4 gap-2">
                <div class="col-span-1">
                    <span class="font-medium text-gray-900">Type(s):</span>
                </div>
                <div class="col-span-3">
                    <span id="detail_intervention_type"></span>
                </div>
            </div>

            {{-- Statut de l'intervention --}}
            <div class="grid grid-cols-4 gap-2">
                <div class="col-span-1">
                    <span class="font-medium text-gray-900">Statut:</span>
                </div>
                <div class="col-span-3">
                    <span id="detail_intervention_status" class="px-3 py-1 text-sm font-semibold rounded-md"></span>
                </div>
            </div>

            {{-- Description de l'intervention --}}
            <div class="bg-gray-50 p-4 rounded-lg text-gray-800 border border-gray-200">
                <strong class="block mb-2 text-gray-900">Description :</strong>
                <p id="detail_intervention_description" class="mt-2 text-gray-700 whitespace-pre-line"></p>
            </div>
        </div>




  

        
        {{-- Boutons pour fermer ou imprimer --}}
        <div class="flex justify-center mt-6">
            <button type="button" onclick="printIntervention()" 
                    class="px-5 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-lg text-sm transition mr-2">
                <i class="fas fa-print mr-1"></i> Imprimer
            </button>
            
            <button type="button" onclick="closeInterventionDetailsModal()" 
                    class="px-5 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm transition">
                Fermer
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner les éléments du modal
    const modal = document.getElementById('remplirContenuModal');
    const closeButtons = document.querySelectorAll('.close-modal');
    
    // Fonctions pour ouvrir et fermer le modal
    function openModal() {
        modal.classList.remove('hidden');
    }
    
    function closeModal() {
        modal.classList.add('hidden');
    }
    
    // Ajouter les écouteurs d'événements pour fermer le modal
    closeButtons.forEach(button => {
        button.addEventListener('click', closeModal);
    });
    
    // Fermer le modal si on clique à l'extérieur du contenu
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Gestion de l'ouverture du modal
    document.querySelectorAll('[data-modal-target="remplirContenuModal"]').forEach(button => {
        button.addEventListener('click', function() {
            const interventionId = this.getAttribute('data-intervention-id');
            document.getElementById('modalInterventionId').value = interventionId;
            
            // Vider le formulaire d'ajout
            document.getElementById('contenuForm').reset();
            
            // Charger tous les détails existants pour cette intervention
            loadInterventionDetails(interventionId);
            
            // Ouvrir le modal
            openModal();
        });
    });
    
    // Fonction pour créer un élément de détail
    function createDetailElement(detail) {
        const detailElement = document.createElement('div');
        detailElement.className = 'bg-gray-50 border border-gray-200 rounded-md p-3';
        
        // Partie visible du détail
        const visibleContent = document.createElement('div');
        visibleContent.className = 'detail-visible';
        visibleContent.innerHTML = `
            <div class="flex justify-between items-center mb-2">
                <strong class="text-gray-800">${detail.type_intervention.nom}</strong>
                <div class="space-x-2">
                    <button type="button" class="edit-button px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-sm" data-detail-id="${detail.id}">Modifier</button>
                    <button type="button" class="delete-button px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 text-sm" data-detail-id="${detail.id}">Supprimer</button>
                </div>
            </div>
            <div class="text-gray-600 detail-content">${detail.contenu}</div>
        `;
        
        // Formulaire d'édition (caché par défaut)
        const editForm = document.createElement('div');
        editForm.className = 'edit-form hidden mt-3';
        editForm.id = `edit-form-${detail.id}`;
        editForm.innerHTML = `
            <form class="update-detail-form" data-detail-id="${detail.id}">
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type d'intervention</label>
                    <select class="w-full border border-gray-300 rounded-md shadow-sm py-1 px-2 text-sm" name="type_intervention_id" required>
                        ${generateTypeOptionsHTML(detail.type_intervention_id)}
                    </select>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea class="w-full border border-gray-300 rounded-md shadow-sm py-1 px-2 text-sm" name="contenu" rows="3" required>${detail.contenu}</textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" class="cancel-edit px-2 py-1 bg-gray-200 text-gray-700 rounded text-sm" data-detail-id="${detail.id}">Annuler</button>
                    <button type="submit" class="px-2 py-1 bg-green-600 text-white rounded text-sm">Enregistrer</button>
                </div>
            </form>
        `;
        
        // Ajouter les deux parties au conteneur principal
        detailElement.appendChild(visibleContent);
        detailElement.appendChild(editForm);
        
        return detailElement;
    }
    
    // Fonction pour générer les options du select pour les types d'intervention
    function generateTypeOptionsHTML(selectedTypeId) {
        let optionsHTML = '';
        document.querySelectorAll('#type_intervention_id option').forEach(option => {
            if (option.value) {
                const selected = option.value == selectedTypeId ? 'selected' : '';
                optionsHTML += `<option value="${option.value}" ${selected}>${option.textContent}</option>`;
            }
        });
        return optionsHTML;
    }
    
    // Fonction pour charger les détails d'intervention
    function loadInterventionDetails(interventionId) {
        fetch(`/technicien/details-interventions/${interventionId}`)
            .then(response => response.json())
            .then(data => {
                const detailsList = document.getElementById('detailsList');
                detailsList.innerHTML = ''; // Vider la liste
                
                if (data.length === 0) {
                    detailsList.innerHTML = '<div class="text-gray-500 text-center py-3">Aucun détail enregistré pour cette intervention.</div>';
                    return;
                }
                
                // Afficher chaque détail
                data.forEach(detail => {
                    const detailElement = createDetailElement(detail);
                    detailsList.appendChild(detailElement);
                });
                
                // Ajouter les événements aux boutons
                setupButtonEvents();
            })
            .catch(error => {
                console.error('Error loading details:', error);
                document.getElementById('detailsList').innerHTML = 
                    '<div class="bg-red-100 text-red-700 p-3 rounded">Erreur lors du chargement des détails.</div>';
            });
    }
    
    // Configurer les événements pour les boutons
    function setupButtonEvents() {
        // Boutons de modification
        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function() {
                const detailId = this.getAttribute('data-detail-id');
                const detailElement = this.closest('.bg-gray-50');
                const visibleContent = detailElement.querySelector('.detail-visible');
                const editForm = document.getElementById(`edit-form-${detailId}`);
                
                visibleContent.classList.add('hidden');
                editForm.classList.remove('hidden');
            });
        });
        
        // Boutons d'annulation
        document.querySelectorAll('.cancel-edit').forEach(button => {
            button.addEventListener('click', function() {
                const detailId = this.getAttribute('data-detail-id');
                const detailElement = this.closest('.bg-gray-50');
                const visibleContent = detailElement.querySelector('.detail-visible');
                const editForm = document.getElementById(`edit-form-${detailId}`);
                
                editForm.classList.add('hidden');
                visibleContent.classList.remove('hidden');
            });
        });
        
        // Formulaires de mise à jour
        document.querySelectorAll('.update-detail-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const detailId = this.getAttribute('data-detail-id');
                const formData = new FormData(this);
                formData.append('detail_id', detailId);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                
                fetch('/technicien/update-detail', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const interventionId = document.getElementById('modalInterventionId').value;
                        loadInterventionDetails(interventionId); // Recharger les détails
                    } else {
                        alert(data.message || 'Une erreur est survenue');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue lors de la mise à jour');
                });
            });
        });
        
        // Boutons de suppression
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Êtes-vous sûr de vouloir supprimer ce détail ?')) {
                    const detailId = this.getAttribute('data-detail-id');
                    
                    fetch(`/technicien/delete-detail/${detailId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const interventionId = document.getElementById('modalInterventionId').value;
                            loadInterventionDetails(interventionId); // Recharger les détails
                        } else {
                            alert(data.message || 'Une erreur est survenue');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Une erreur est survenue lors de la suppression');
                    });
                }
            });
        });
    }
    
    // Gestion de la soumission du formulaire pour ajouter un nouveau détail
    document.getElementById('contenuForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('intervention_id', document.getElementById('modalInterventionId').value);
        formData.append('type_intervention_id', document.getElementById('type_intervention_id').value);
        formData.append('contenu', document.getElementById('contenu').value);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        fetch('/technicien/add-detail', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Réinitialiser le formulaire
                document.getElementById('contenuForm').reset();
                
                // Recharger les détails
                const interventionId = document.getElementById('modalInterventionId').value;
                loadInterventionDetails(interventionId);
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue lors de l\'ajout');
        });
    });
});

// Fonctions pour gérer le modal des détails d'intervention
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

function updateStatusColor(status) {
    const statusElement = document.getElementById("detail_intervention_status");
    
    statusElement.className = "px-3 py-1 text-sm font-semibold rounded-md text-white";
    
    if (status === "En attente") {
        statusElement.classList.add("bg-yellow-500");
    } else if (status === "En cours") {
        statusElement.classList.add("bg-blue-500");
    } else if (status === "Terminé") {
        statusElement.classList.add("bg-green-500");
    }
    
    statusElement.textContent = status;
}

function printIntervention() {
    // Récupération des données de l'intervention
    const id = document.getElementById('detail_intervention_id').textContent.trim();
    const user = document.getElementById('detail_intervention_user').textContent.trim();
    const titre = document.getElementById('detail_intervention_titre').textContent.trim();
    const description = document.getElementById('detail_intervention_description').textContent.trim();
    const date = document.getElementById('detail_intervention_creation_date').textContent.trim();
    const type = document.getElementById('detail_intervention_type').textContent.trim();
    const status = document.getElementById('detail_intervention_status').textContent.trim().toLowerCase();

    // Utiliser Tailwind pour l'impression
    const printWindow = window.open('', '_blank');
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Intervention #${id}</title>
            <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
            <style>
                @page { size: A4; margin: 15mm; }
                body { font-family: 'Helvetica', 'Arial', sans-serif; }
                @media print {
                    .print-button { display: none; }
                }
            </style>
        </head>
        <body class="bg-gray-50 p-4">
            <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- En-tête -->
                <div class="text-center border-b p-4 bg-gray-50">
                    <div class="mb-2">
                        <!-- Logo placeholder -->
                        <div class="h-12 w-32 mx-auto mb-2 bg-gray-200 flex items-center justify-center text-gray-500">LOGO</div>
                    </div>
                    <h1 class="text-xl font-bold text-gray-800">Intervention #${id}</h1>
                    <p class="text-sm text-gray-500">Document généré le ${new Date().toLocaleDateString('fr-FR')}</p>
                </div>
                
                <!-- Informations de l'intervention -->
                <div class="p-6">
                    <h2 class="text-lg font-semibold mb-4 pb-2 border-b text-gray-700">Informations de l'intervention</h2>
                    
                    <div class="grid grid-cols-2 gap-y-2">
                        <div class="font-medium text-gray-600">ID:</div>
                        <div>${id}</div>
                        
                        <div class="font-medium text-gray-600">Utilisateur:</div>
                        <div>${user}</div>
                        
                        <div class="font-medium text-gray-600">Titre:</div>
                        <div>${titre}</div>
                        
                        <div class="font-medium text-gray-600">Date de création:</div>
                        <div>${date}</div>
                        
                        <div class="font-medium text-gray-600">Type:</div>
                        <div>${type}</div>
                        
                        <div class="font-medium text-gray-600">Statut:</div>
                        <div><span class="px-2 py-1 ${status === 'terminé' ? 'bg-green-500' : status === 'en cours' ? 'bg-blue-500' : 'bg-yellow-500'} text-white text-xs rounded-full">${status}</span></div>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="font-medium text-gray-600">Description:</h3>
                        <div class="p-3 bg-gray-50 rounded mt-2 text-sm">${description}</div>
                    </div>
                </div>
                
                <!-- Pied de page -->
                <div class="p-4 text-center border-t text-xs text-gray-500">
                    <p>Ce document est confidentiel et destiné uniquement à usage interne.</p>
                </div>
            </div>
            
            <div class="text-center mt-6 print-button">
                <button onclick="window.print()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Imprimer
                </button>
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    setTimeout(() => {
        printWindow.focus();
    }, 500);
}

// Fermer les modals en cliquant en dehors
window.addEventListener('click', function(e) {
    const modalRemplir = document.getElementById('remplirContenuModal');
    const modalIntervention = document.getElementById('interventionDetailsModal');
    
    if (e.target === modalRemplir) {
        modalRemplir.classList.add('hidden');
    }
    
    if (e.target === modalIntervention) {
        closeInterventionDetailsModal();
    }
});
</script>
@endsection





