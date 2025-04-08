@extends('layouts.admin')

@section('content')
<div class="py-6 h-full">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md sm:rounded-lg p-6">

            <h2 class="text-2xl font-semibold mb-6">Statistiques Générales</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-green-100 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-bold">Utilisateurs</h3>
                    <p class="text-3xl font-semibold">{{ number_format($totalUsers) }}</p>
                </div>

                <div class="bg-blue-100 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-bold">Administrateurs</h3>
                    <p class="text-3xl font-semibold">{{ number_format($totalAdmins) }}</p>
                </div>

                <div class="bg-yellow-100 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-bold">Techniciens</h3>
                    <p class="text-3xl font-semibold">{{ number_format($totalTechnicians) }}</p>
                </div>

                <div class="bg-red-100 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-bold">Interventions</h3>
                    <p class="text-3xl font-semibold">{{ number_format($totalInterventions) }}</p>
                </div>

                <div class="bg-purple-100 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-bold">Services</h3>
                    <p class="text-3xl font-semibold">{{ number_format($totalServices) }}</p>
                </div>
            </div>

            <!-- Répartition Utilisateurs -->
            <div class="mt-10 bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-4">Répartition des Utilisateurs</h3>
                <canvas id="usersChart"></canvas>
            </div>

            <!-- Répartition par service / avec filtres -->
            <div class="mt-10 bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-4">Répartition par Service / Division</h3>

                <div class="flex flex-wrap gap-4 mb-6">
                    <div class="w-full md:w-1/3">
                        <label for="dataType" class="block text-sm font-medium text-gray-700 mb-1">Type de données</label>
                        <select id="dataType" class="block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="interventions">Interventions</option>
                            <option value="admins">Administrateurs</option>
                            <option value="technicians">Techniciens</option>
                            <option value="users">Utilisateurs</option>
                        </select>
                    </div>

                    <div class="w-full md:w-1/3">
                        <label for="timeFrame" class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                        <select id="timeFrame" class="block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="all">Toutes les données</option>
                            <option value="7days">7 derniers jours</option>
                            <option value="30days">30 derniers jours</option>
                            <option value="month">Ce mois</option>
                            <option value="year">Cette année</option>
                        </select>
                    </div>

                    <div class="w-full md:w-1/3 flex items-end">
                        <button id="applyFilters" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md shadow">
                            Appliquer les filtres
                        </button>
                    </div>
                </div>

                <canvas id="serviceDistributionChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Graphique utilisateurs
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        new Chart(usersCtx, {
            type: 'bar',
            data: {
                labels: ['Utilisateurs', 'Administrateurs', 'Techniciens'],
                datasets: [{
                    label: 'Nombre',
                    data: [{{ $totalUsers }}, {{ $totalAdmins }}, {{ $totalTechnicians }}],
                    backgroundColor: ['#60a5fa', '#f87171', '#facc15'],
                    borderColor: ['#3b82f6', '#ef4444', '#eab308'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0 // Empêche les virgules et force l'affichage des entiers
                        }
                    }
                }
            }
        });

        // Graphique par service dynamique
        let serviceChart;

        function loadServiceChart() {
            const dataType = document.getElementById('dataType').value;
            const timeFrame = document.getElementById('timeFrame').value;

            fetch(`/stats/service-distribution?dataType=${dataType}&timeFrame=${timeFrame}`)
                .then(response => response.json())
                .then(data => {
                    if (serviceChart) {
                        serviceChart.destroy();
                    }

                    const ctx = document.getElementById('serviceDistributionChart').getContext('2d');
                    serviceChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: data.title,
                                data: data.values.map(value => Math.round(value)), // Forcer à arrondir les données
                                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0 // Empêche les virgules
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error("Erreur de chargement du graphique :", error);
                });
        }

        // Chargement initial
        loadServiceChart();

        // Rechargement au clic
        document.getElementById('applyFilters').addEventListener('click', function (e) {
            e.preventDefault();
            loadServiceChart();
        });
    });
</script>
@endsection
