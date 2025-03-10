@extends('layouts.user')

@section('content')
    <div class="py-6 h-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Mes Statistiques</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                    <!-- Nombre total d'interventions de l'utilisateur -->
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-bold">Mes Interventions</h3>
                        <p class="text-3xl font-semibold">{{ $userInterventions }}</p>
                    </div>
                </div>

                <!-- Filtres et sélection du graphique -->
                <div class="mt-6">
                    <label for="graphType">Sélectionner un graphique :</label>
                    <select id="graphType" class="border p-2 rounded">
                        <option value="interventions">Nombre d'interventions par service</option>
                        <option value="users">Nombre d'employés par rôle</option>
                    </select>

                    <label for="startDate" class="ml-4">Date de début :</label>
                    <input type="date" id="startDate" class="border p-2 rounded">

                    <label for="endDate" class="ml-4">Date de fin :</label>
                    <input type="date" id="endDate" class="border p-2 rounded">

                    <button onclick="updateGraph()" class="ml-4 bg-blue-500 text-white px-4 py-2 rounded">
                        Filtrer
                    </button>
                </div>

                <!-- Graphique -->
                <div class="mt-6">
                    <canvas id="statsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Ajout du script Chart.js et du script JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chart;

    function updateGraph() {
        let type = document.getElementById('graphType').value;
        let startDate = document.getElementById('startDate').value;
        let endDate = document.getElementById('endDate').value;

        fetch(`/admin/statistics/data?type=${type}&start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                let labels = data.map(item => item.service || item.profile);
                let counts = data.map(item => item.count);

                if (chart) {
                    chart.destroy(); // Supprime l'ancien graphique
                }

                let ctx = document.getElementById('statsChart').getContext('2d');
                chart = new Chart(ctx, {
                    type: 'bar', // Modifier en 'line' ou 'pie' si besoin
                    data: {
                        labels: labels,
                        datasets: [{
                            label: type === 'interventions' ? 'Interventions' : 'Employés',
                            data: counts,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Erreur lors du chargement des données:', error));
    }

    document.addEventListener("DOMContentLoaded", updateGraph);
</script>
