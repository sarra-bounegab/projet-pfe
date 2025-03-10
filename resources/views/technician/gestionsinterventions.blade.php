@extends('layouts.technicien')

@section('content')
<div class="bg-white shadow-md sm:rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-4">Mes Interventions</h2>

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
                    <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                        <td class="border px-4 py-2">{{ $intervention->id }}</td>
                        <td class="border px-4 py-2">{{ $intervention->user->name }}</td>
                        <td class="border px-4 py-2">{{ $intervention->created_at->format('d/m/Y') }}</td>
                        <td class="border px-4 py-2">
                            {{ $intervention->typeIntervention ? $intervention->typeIntervention->type : 'Non défini' }}
                        </td>
                        <td class="border px-4 py-2">
                            @if ($intervention->status == 'en cours')
                                <span class="px-2 py-1 bg-yellow-500 text-white rounded">En cours</span>
                            @elseif ($intervention->status == 'terminée')
                                <span class="px-2 py-1 bg-green-500 text-white rounded">Terminée</span>
                            @else
                                <span class="px-2 py-1 bg-gray-500 text-white rounded">En attente</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2 text-center">
                            <button type="button" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition"
                                onclick="ouvrirModalTaches({{ $intervention->id }})">
                                + Ajouter un Rapport & Tâches
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal pour ajouter rapport et tâches --}}
<div id="modal-taches" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white shadow-2xl rounded-2xl w-full max-w-xl p-6 relative">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Ajouter un Rapport et des Tâches</h2>

        <form id="formRapport" method="POST" action="{{ route('rapports.store') }}">
            @csrf
            <input type="hidden" id="intervention_id" name="intervention_id">

            {{-- Champ rapport --}}
            <div class="mb-4">
                <label for="contenu" class="block text-gray-700 font-semibold mb-2">Contenu du rapport :</label>
                <textarea name="contenu" id="contenu" 
                    class="border rounded-lg px-4 py-2 w-full focus:ring focus:ring-blue-200" 
                    rows="3" placeholder="Décrire l'intervention..."></textarea>
            </div>

            {{-- Liste des tâches --}}
            <div id="taches-container" class="space-y-2 mb-4 max-h-48 overflow-y-auto pr-2">
                {{-- Les tâches ajoutées dynamiquement apparaîtront ici --}}
            </div>

            {{-- Ajout d'une nouvelle tâche --}}
            <div class="flex items-center gap-2 mb-4">
                <input type="text" id="nouvelle-tache" 
                    class="border rounded-lg px-4 py-2 w-full focus:ring focus:ring-green-200" 
                    placeholder="Ajouter une nouvelle tâche...">
                <button type="button" onclick="ajouterTache()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    Ajouter
                </button>
            </div>

            {{-- Boutons actions --}}
            <div class="flex justify-between mt-6">
                <button type="button" onclick="fermerModalTaches()" 
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Annuler
                </button>
                <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Enregistrer
                </button>
            </div>
        </form>

        {{-- Bouton de fermeture modal --}}
        <button onclick="fermerModalTaches()" 
            class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-xl">
            &times;
        </button>
    </div>
</div>




<script>
   let interventionId = null;

function ouvrirModalTaches(id) {
    interventionId = id;
    document.getElementById('intervention_id').value = id;
    document.getElementById('modal-taches').classList.remove('hidden');

    console.log("Chargement des tâches pour intervention_id:", id);

    fetch(`/get-taches?intervention_id=${id}`)
        .then(response => response.json())
        .then(data => {
            console.log("Réponse reçue:", data); 

            let container = document.getElementById('taches-container');
            container.innerHTML = '';
            if (data.success && data.taches.length > 0) {
                data.taches.forEach(tache => afficherTache(tache.description, tache.id)); // On passe l'id ici
            } else {
                container.innerHTML = '<p class="text-gray-500">Aucune tâche trouvée.</p>';
            }
        })
        .catch(error => {
            console.error('Erreur AJAX:', error);
            document.getElementById('taches-container').innerHTML = 
                '<p class="text-red-500">Erreur lors du chargement des tâches.</p>';
        });
}

function fermerModalTaches() {
    document.getElementById('modal-taches').classList.add('hidden');
}

let compteurTaches = 0;

function ajouterTache() {
    let tacheInput = document.getElementById('nouvelle-tache');
    let tacheTexte = tacheInput.value.trim();

    if (tacheTexte !== "") {
        compteurTaches++; 

        let container = document.getElementById('taches-container');

        let divTache = document.createElement('div');
        divTache.classList.add('flex', 'justify-between', 'items-center', 'bg-gray-100', 'rounded-full', 'px-4', 'py-2', 'shadow', 'hover:bg-gray-200', 'transition', 'gap-2');

        let labelTache = document.createElement('span');
        labelTache.classList.add('bg-gray-500', 'text-white', 'rounded-full', 'px-3', 'py-1', 'text-sm', 'font-semibold');
        labelTache.textContent = 'Tâche ' + compteurTaches;

        let spanTache = document.createElement('span');
        spanTache.classList.add('font-medium', 'text-gray-700', 'flex-1');
        spanTache.textContent = tacheTexte;

        let inputHidden = document.createElement('input');
        inputHidden.type = 'hidden';
        inputHidden.name = 'taches[]';
        inputHidden.value = tacheTexte;

        let btnSupprimer = document.createElement('button');
        btnSupprimer.innerHTML = "❌";
        btnSupprimer.classList.add('text-red-500', 'hover:text-red-700', 'text-lg');
        btnSupprimer.onclick = function () {
            container.removeChild(divTache);
            reNumeroTaches(); 
        };

        divTache.appendChild(labelTache);
        divTache.appendChild(spanTache);
        divTache.appendChild(inputHidden);
        divTache.appendChild(btnSupprimer);
        container.appendChild(divTache);

        tacheInput.value = "";
    }
}

function reNumeroTaches() {
    let labels = document.querySelectorAll('#taches-container span.bg-gray-500');
    compteurTaches = 0;
    labels.forEach((label, index) => {
        compteurTaches++;
        label.textContent = 'Tâche ' + compteurTaches;
    });
}


function afficherTache(description, tache_id) {
    let container = document.getElementById('taches-container');

    let divTache = document.createElement('div');
    divTache.classList.add('flex', 'justify-between', 'items-center', 'p-2', 'border', 'rounded-lg', 'bg-gray-100', 'shadow');

    let spanTache = document.createElement('span');
    spanTache.textContent = description;

    let inputHidden = document.createElement('input');
    inputHidden.type = 'hidden';
    inputHidden.name = 'taches[]';
    inputHidden.value = description;

    let btnSupprimer = document.createElement('button');
    btnSupprimer.textContent = "❌";
    btnSupprimer.classList.add('text-red-500', 'hover:text-red-700', 'text-sm');
    btnSupprimer.onclick = function () {
        supprimerTache(divTache, tache_id); // Appel de la fonction suppression
    };

    divTache.appendChild(spanTache);
    divTache.appendChild(inputHidden);
    divTache.appendChild(btnSupprimer);
    container.appendChild(divTache);
}


function supprimerTache(tacheElement, tache_id) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette tâche ?")) {

        fetch(`/supprimer-tache/${tache_id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                tacheElement.remove(); 
                console.log('Tâche supprimée avec succès');
            } else {
                alert('Erreur lors de la suppression : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la suppression:', error);
            alert('Erreur de connexion. Veuillez réessayer.');
        });
    }
}

</script>


@endsection
