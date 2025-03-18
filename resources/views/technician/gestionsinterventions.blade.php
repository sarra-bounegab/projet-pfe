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
                        <td class="border px-4 py-2 text-center space-y-2">
    <!-- Bouton Ajouter un rapport -->
    <button onclick="ouvrirModalTaches({{ $intervention->id }})"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
        + Ajouter un Rapport
    </button>

    <form action="{{ route('intervention.cloturer', $intervention->id) }}" method="POST" style="display: inline;">

    @csrf
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition" onclick="return confirm('Voulez-vous vraiment clôturer cette intervention ?')">
        Clôturer
    </button>
</form>


</td>


                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script>
    function cloturerIntervention(interventionId, buttonElement) {
        if (confirm('Êtes-vous sûr de vouloir clôturer cette intervention ?')) {
            fetch(`/intervention/${interventionId}/cloturer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Mise à jour du statut dans le tableau sans recharger la page
                    const statusCell = buttonElement.closest('tr').querySelector('td:nth-child(5)');
                    statusCell.innerHTML = '<span class="px-2 py-1 bg-red-600 text-white rounded">Clôturée</span>';
                    
                    // Optionnel: désactiver les boutons après clôture
                    buttonElement.disabled = true;
                    const addButton = buttonElement.parentElement.querySelector('button:first-child');
                    addButton.disabled = true;
                    addButton.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    alert('Erreur lors de la clôture.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur réseau.');
            });
        }
    }
</script>


<div id="modal-taches" class="hidden fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-4">Rapport d'Intervention</h2>

        <form id="formRapport">
            <input type="hidden" id="intervention_id">

            <label for="contenu">Contenu du rapport :</label>
            <textarea id="contenu" class="w-full p-2 border rounded mb-4" required></textarea>

            <label for="nouvelle-tache">Ajouter une tâche :</label>
            <input type="text" id="nouvelle-tache" class="w-full p-2 border rounded mb-2">
            <button type="button" onclick="ajouterTache()" class="bg-blue-500 text-white p-2 rounded">Ajouter</button>

            <div id="taches-container" class="mt-4"></div>

            <div class="mt-4 flex justify-end">
                <button type="button" onclick="fermerModalTaches()" class="mr-2 bg-gray-300 p-2 rounded">Annuler</button>
                <button type="submit" class="bg-blue-500 text-white p-2 rounded">Enregistrer</button>
            </div>
        </form>
    </div>
</div>




<script>
    let interventionIdSelected = null;
    let taches = [];

    function ouvrirModalTaches(interventionId) {
        interventionIdSelected = interventionId;
        document.getElementById('intervention_id').value = interventionId;
        document.getElementById('contenu').value = ''; // Réinitialise le champ contenu
        taches = [];
        document.getElementById('taches-container').innerHTML = '';

        // Charger le rapport et les tâches existantes
        fetch(`/intervention/${interventionId}/rapport`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.rapport) {
                        document.getElementById('contenu').value = data.rapport.contenu;
                    }
                    if (data.taches.length > 0) {
                        data.taches.forEach(tache => {
                            ajouterTacheHTML(tache.description);
                            taches.push(tache.description);
                        });
                    }
                }
            });

        document.getElementById('modal-taches').classList.remove('hidden');
    }

    function fermerModalTaches() {
        document.getElementById('modal-taches').classList.add('hidden');
    }

    function ajouterTache() {
        const input = document.getElementById('nouvelle-tache');
        const description = input.value.trim();
        if (description) {
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
            <button onclick="supprimerTache('${description}')" class="text-red-500 hover:text-red-700">Supprimer</button>
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
                location.reload();
            } else {
                alert('Erreur : ' + data.message);
            }
        });
    });
</script>




@endsection
