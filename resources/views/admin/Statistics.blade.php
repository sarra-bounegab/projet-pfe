@extends('layouts.admin')

@section('content')

<div class="py-6 h-full">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md sm:rounded-lg p-6">

            <h2 class="text-2xl font-semibold mb-6">Statistiques Générales</h2>

            <!-- Cartes récapitulatives -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-green-50 p-4 rounded-xl shadow-sm flex flex-col items-start">
                    <h3 class="text-sm font-medium text-gray-600">Utilisateurs</h3>
                    <p class="text-2xl font-bold text-green-700">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-xl shadow-sm flex flex-col items-start">
                    <h3 class="text-sm font-medium text-gray-600">Administrateurs</h3>
                    <p class="text-2xl font-bold text-blue-700">{{ number_format($totalAdmins) }}</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-xl shadow-sm flex flex-col items-start">
                    <h3 class="text-sm font-medium text-gray-600">Techniciens</h3>
                    <p class="text-2xl font-bold text-yellow-700">{{ number_format($totalTechnicians) }}</p>
                </div>
                <div class="bg-red-50 p-4 rounded-xl shadow-sm flex flex-col items-start">
                    <h3 class="text-sm font-medium text-gray-600">Interventions</h3>
                    <p class="text-2xl font-bold text-red-700">{{ number_format($totalInterventions) }}</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-xl shadow-sm flex flex-col items-start">
                    <h3 class="text-sm font-medium text-gray-600">Services</h3>
                    <p class="text-2xl font-bold text-purple-700">{{ number_format($totalServices) }}</p>
                </div>
            </div>

            <!-- Statut des interventions -->
<div class="mt-10 bg-white p-6 rounded-lg shadow">
    <h3 class="text-xl font-semibold mb-4">Interventions par statut</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
        <!-- Donut Chart -->
        <div class="flex justify-center">
            <canvas id="statusChart" class="max-w-[280px] max-h-[280px]"></canvas>
        </div>

        <!-- Stat Blocks -->
        <div class="space-y-3 text-sm">
            <!-- En attente -->
            <div class="flex items-center justify-between bg-yellow-100 px-4 py-3 rounded-lg shadow-sm">
                <div class="flex items-center gap-2 text-yellow-700">
                    <span class="text-lg">⏳</span>
                    <span>En attente</span>
                </div>
                <span class="text-lg font-bold text-yellow-700">{{ $statusCounts['en attente'] ?? 0 }}</span>
            </div>

            <!-- En cours -->
            <div class="flex items-center justify-between bg-blue-100 px-4 py-3 rounded-lg shadow-sm">
                <div class="flex items-center gap-2 text-blue-700">
                    <span class="text-lg">🛠️</span>
                    <span>En cours</span>
                </div>
                <span class="text-lg font-bold text-blue-700">{{ $statusCounts['en cours'] ?? 0 }}</span>
            </div>

            <!-- Résolue -->
            <div class="flex items-center justify-between bg-green-100 px-4 py-3 rounded-lg shadow-sm">
                <div class="flex items-center gap-2 text-green-700">
                    <span class="text-lg">✅</span>
                    <span>Résolue</span>
                </div>
                <span class="text-lg font-bold text-green-700">{{ $statusCounts['résolue'] ?? 0 }}</span>
            </div>

            <!-- Annulée -->
            <div class="flex items-center justify-between bg-red-100 px-4 py-3 rounded-lg shadow-sm">
                <div class="flex items-center gap-2 text-red-700">
                    <span class="text-lg">❌</span>
                    <span>Annulée</span>
                </div>
                <span class="text-lg font-bold text-red-700">{{ $statusCounts['annulée'] ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>

            <!-- Graphique utilisateurs -->
            <div class="mt-10 bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-4">Répartition des Utilisateurs</h3>
                <canvas id="usersChart"></canvas>
            </div>

            <!-- Répartition par service -->
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

<!-- Donut Chart -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('/statistics/interventions/status')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('statusChart').getContext('2d');
            const total = data.values.reduce((a, b) => a + b, 0);
            const percentageLabels = data.labels.map((label, i) => {
                const percent = ((data.values[i] / total) * 100).toFixed(1);
                return `${label} (${percent}%)`;
            });

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: percentageLabels,
                    datasets: [{
                        label: 'Statuts des interventions',
                        data: data.values,
                        backgroundColor: [
                            'rgba(234, 179, 8, 0.7)',   // En attente
                            'rgba(59, 130, 246, 0.7)',  // En cours
                            'rgba(34, 197, 94, 0.7)',   // Résolue
                            'rgba(239, 68, 68, 0.7)'    // Annulée
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const value = context.parsed;
                                    const percent = ((value / total) * 100).toFixed(1);
                                    return `${context.label}: ${value} (${percent}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });
});
</script>


<!-- Users Bar Chart -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
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
                            precision: 0
                        }
                    }
                }
            }
        });

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
                                data: data.values.map(v => Math.round(v)),
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
                                        precision: 0
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

        loadServiceChart();
        document.getElementById('applyFilters').addEventListener('click', function (e) {
            e.preventDefault();
            loadServiceChart();
        });
    });
</script>
@endsection
