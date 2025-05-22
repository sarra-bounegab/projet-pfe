@extends('layouts.admin')

@section('content')
<div class="py-6 h-full">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-6 flex items-center">
                <i class="fa-solid fa-chart-pie mr-2 text-indigo-600"></i>
                Statistiques Générales
            </h2>

            <!-- Cartes récapitulatives -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Carte Utilisateurs -->
                <div class="bg-green-50 p-4 rounded-xl shadow-sm border border-green-100">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fa-solid fa-users text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-600">Utilisateurs</h3>
                            <p class="text-2xl font-bold text-green-700">{{ number_format($totalUsers) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Carte Administrateurs -->
                <div class="bg-blue-50 p-4 rounded-xl shadow-sm border border-blue-100">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fa-solid fa-user-shield text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-600">Administrateurs</h3>
                            <p class="text-2xl font-bold text-blue-700">{{ number_format($totalAdmins) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Carte Techniciens -->
                <div class="bg-yellow-50 p-4 rounded-xl shadow-sm border border-yellow-100">
                    <div class="flex items-center gap-3">
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fa-solid fa-screwdriver-wrench text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-600">Techniciens</h3>
                            <p class="text-2xl font-bold text-yellow-700">{{ number_format($totalTechnicians) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Carte Interventions -->
                <div class="bg-red-50 p-4 rounded-xl shadow-sm border border-red-100">
                    <div class="flex items-center gap-3">
                        <div class="bg-red-100 p-3 rounded-full">
                            <i class="fa-solid fa-ticket text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-600">Interventions</h3>
                            <p class="text-2xl font-bold text-red-700">{{ number_format($totalInterventions) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Carte Services -->
                <div class="bg-purple-50 p-4 rounded-xl shadow-sm border border-purple-100">
                    <div class="flex items-center gap-3">
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fa-solid fa-building text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-600">Services</h3>
                            <p class="text-2xl font-bold text-purple-700">{{ number_format($totalServices) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statut des interventions -->
            <div class="mt-10 bg-white p-6 rounded-lg shadow border border-gray-100">
                <h3 class="text-xl font-semibold mb-4 flex items-center">
                    <i class="fa-solid fa-chart-simple mr-2 text-indigo-600"></i>
                    Interventions par statut
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <!-- Donut Chart -->
                    <div class="flex justify-center">
                        <canvas id="statusChart" class="max-w-[280px] max-h-[280px]"></canvas>
                    </div>

                    <!-- Stat Blocks -->
                    <div class="space-y-3 text-sm">
                        <!-- En attente -->
                        <div class="flex items-center justify-between bg-yellow-50 px-4 py-3 rounded-lg border border-yellow-100">
                            <div class="flex items-center gap-2 text-yellow-700">
                                <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                                <span>En attente</span>
                            </div>
                            <span class="text-lg font-bold text-yellow-700">{{ $statusCounts['en attente'] ?? 0 }}</span>
                        </div>

                        <!-- En cours -->
                        <div class="flex items-center justify-between bg-blue-50 px-4 py-3 rounded-lg border border-blue-100">
                            <div class="flex items-center gap-2 text-blue-700">
                                <i class="fa-solid fa-hammer text-lg"></i>
                                <span>En cours</span>
                            </div>
                            <span class="text-lg font-bold text-blue-700">{{ $statusCounts['en cours'] ?? 0 }}</span>
                        </div>

                        <!-- Terminée -->
                        <div class="flex items-center justify-between bg-green-50 px-4 py-3 rounded-lg border border-green-100">
                            <div class="flex items-center gap-2 text-green-700">
                                <i class="fa-solid fa-circle-check text-lg"></i>
                                <span>Terminée</span>
                            </div>
                            <span class="text-lg font-bold text-green-700">{{ $statusCounts['terminée'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Répartition par service améliorée -->
            <div class="mt-10 bg-white p-6 rounded-lg shadow border border-gray-100">
                <h3 class="text-xl font-semibold mb-4 flex items-center">
                    <i class="fa-solid fa-chart-bar mr-2 text-indigo-600"></i>
                    Analyse par Service
                </h3>

                <!-- Filtres améliorés -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Type de données -->
                        <div>
                            <label for="dataType" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fa-solid fa-filter mr-2 text-indigo-500"></i>
                                Type de données
                            </label>
                            <select id="dataType" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="admins">
                                    <i class="fa-solid fa-user-shield mr-2"></i> Administrateurs
                                </option>
                                <option value="technicians">
                                    <i class="fa-solid fa-screwdriver-wrench mr-2"></i> Techniciens
                                </option>
                                <option value="users">
                                    <i class="fa-solid fa-users mr-2"></i> Utilisateurs
                                </option>
                            </select>
                        </div>

                        <!-- Période -->
                        <div>
                            <label for="timeFrame" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fa-solid fa-calendar-days mr-2 text-indigo-500"></i>
                                Période
                            </label>
                            <select id="timeFrame" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="all">
                                    <i class="fa-solid fa-infinity mr-2"></i> Toutes les données
                                </option>
                                <option value="7days">
                                    <i class="fa-solid fa-calendar-week mr-2"></i> 7 derniers jours
                                </option>
                                <option value="30days">
                                    <i class="fa-solid fa-calendar mr-2"></i> 30 derniers jours
                                </option>
                                <option value="month">
                                    <i class="fa-solid fa-calendar-check mr-2"></i> Ce mois
                                </option>
                                <option value="year">
                                    <i class="fa-solid fa-calendar-star mr-2"></i> Cette année
                                </option>
                            </select>
                        </div>

                        <!-- Division -->
                        <div>
                            <label for="divisionFilter" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <i class="fa-solid fa-sitemap mr-2 text-indigo-500"></i>
                                Division
                            </label>
                            <select id="divisionFilter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">
                                    <i class="fa-solid fa-layer-group mr-2"></i> Toutes les divisions
                                </option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}">
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bouton Analyser -->
                        <div class="flex items-end">
                            <button id="applyFilters" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md shadow transition-colors duration-200 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Analyser
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Indicateur de chargement -->
                <div id="loadingIndicator" class="hidden text-center py-8">
                    <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-indigo-500 bg-indigo-100">
                        <i class="fa-solid fa-spinner fa-spin mr-3"></i>
                        Chargement des données...
                    </div>
                </div>

                <!-- Message d'erreur -->
                <div id="errorMessage" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                    <div class="flex items-center">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                        <span id="errorText">Une erreur est survenue lors du chargement des données.</span>
                    </div>
                </div>

                <!-- Graphique -->
                <div id="chartContainer">
                    <canvas id="serviceDistributionChart" style="max-height: 400px;"></canvas>
                </div>

                <!-- Statistiques résumées -->
                <div id="chartSummary" class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-100 hidden">
                    <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                        <i class="fa-solid fa-chart-line mr-2"></i>
                        Résumé
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="text-blue-700 flex items-center">
                            <i class="fa-solid fa-calculator mr-2"></i>
                            <span><span class="font-medium">Total:</span> <span id="totalCount">0</span></span>
                        </div>
                        <div class="text-blue-700 flex items-center">
                            <i class="fa-solid fa-arrow-up mr-2"></i>
                            <span><span class="font-medium">Maximum:</span> <span id="maxValue">0</span></span>
                        </div>
                        <div class="text-blue-700 flex items-center">
                            <i class="fa-solid fa-divide mr-2"></i>
                            <span><span class="font-medium">Moyenne:</span> <span id="avgValue">0</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
<!-- Remplacez 'your-code.js' par votre propre code Font Awesome -->

<script>
    // Graphique des statuts
    const statusChart = document.getElementById('statusChart').getContext('2d');
    const total = {{ $statusCounts['en attente'] + $statusCounts['en cours'] + $statusCounts['terminée'] }};
    const statusData = {
        labels: ['En attente', 'En cours', 'Terminée'],
        datasets: [{
            data: [
                {{ $statusCounts['en attente'] }},
                {{ $statusCounts['en cours'] }},
                {{ $statusCounts['terminée'] }}
            ],
            backgroundColor: ['#facc15', '#60a5fa', '#4ade80'],
            borderColor: '#fff',
            borderWidth: 2
        }]
    };

    const statusOptions = {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle',
                    padding: 20
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const value = context.raw;
                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                        return `${value} interventions (${percentage}%)`;
                    }
                }
            }
        }
    };

    new Chart(statusChart, {
        type: 'doughnut',
        data: statusData,
        options: statusOptions
    });

    // Gestion du graphique de service amélioré
    document.addEventListener("DOMContentLoaded", function () {
        let serviceChart;
        const loadingIndicator = document.getElementById('loadingIndicator');
        const errorMessage = document.getElementById('errorMessage');
        const chartSummary = document.getElementById('chartSummary');

        function showLoading() {
            loadingIndicator.classList.remove('hidden');
            errorMessage.classList.add('hidden');
            chartSummary.classList.add('hidden');
        }

        function hideLoading() {
            loadingIndicator.classList.add('hidden');
        }

        function showError(message) {
            errorMessage.classList.remove('hidden');
            document.getElementById('errorText').textContent = message;
        }

        function updateSummary(data) {
            if (data.values && data.values.length > 0) {
                const total = data.values.reduce((sum, val) => sum + val, 0);
                const max = Math.max(...data.values);
                const avg = (total / data.values.length).toFixed(1);

                document.getElementById('totalCount').textContent = total;
                document.getElementById('maxValue').textContent = max;
                document.getElementById('avgValue').textContent = avg;
                chartSummary.classList.remove('hidden');
            } else {
                chartSummary.classList.add('hidden');
            }
        }

        function getChartColors(dataType) {
            const colorSchemes = {
                admins: {
                    background: 'rgba(239, 68, 68, 0.6)',
                    border: 'rgba(239, 68, 68, 1)'
                },
                technicians: {
                    background: 'rgba(234, 179, 8, 0.6)',
                    border: 'rgba(234, 179, 8, 1)'
                },
                users: {
                    background: 'rgba(34, 197, 94, 0.6)',
                    border: 'rgba(34, 197, 94, 1)'
                }
            };
            return colorSchemes[dataType] || colorSchemes.interventions;
        }

        function loadServiceChart() {
            showLoading();

            const dataType = document.getElementById('dataType').value;
            const timeFrame = document.getElementById('timeFrame').value;
            const divisionId = document.getElementById('divisionFilter').value;

            let url = `/stats/service-distribution?dataType=${dataType}&timeFrame=${timeFrame}`;
            if (divisionId) {
                url += `&division_id=${divisionId}`;
            }

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur du serveur');
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();

                    if (data.error) {
                        showError(data.error);
                        return;
                    }

                    if (!data.labels || data.labels.length === 0) {
                        showError('Aucune donnée disponible pour les filtres sélectionnés');
                        if (serviceChart) {
                            serviceChart.destroy();
                            serviceChart = null;
                        }
                        return;
                    }

                    // Détruire l'ancien graphique
                    if (serviceChart) {
                        serviceChart.destroy();
                    }

                    const colors = getChartColors(dataType);
                    const ctx = document.getElementById('serviceDistributionChart').getContext('2d');

                    serviceChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: data.title,
                                data: data.values,
                                backgroundColor: colors.background,
                                borderColor: colors.border,
                                borderWidth: 1,
                                borderRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true,
                                        pointStyle: 'rectRounded'
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    borderColor: colors.border,
                                    borderWidth: 1,
                                    usePointStyle: true
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                x: {
                                    ticks: {
                                        font: {
                                            size: 11
                                        },
                                        maxRotation: 45,
                                        minRotation: 45
                                    }
                                }
                            }
                        }
                    });

                    updateSummary(data);
                })
                .catch(error => {
                    hideLoading();
                    console.error("Erreur de chargement du graphique :", error);
                    showError('Erreur de connexion. Veuillez réessayer.');
                });
        }

        // Chargement initial
        loadServiceChart();

        // Event listener pour le bouton
        document.getElementById('applyFilters').addEventListener('click', function (e) {
            e.preventDefault();
            loadServiceChart();
        });

        // Event listeners pour les changements de filtres
        ['dataType', 'timeFrame', 'divisionFilter'].forEach(id => {
            document.getElementById(id).addEventListener('change', loadServiceChart);
        });
    });
</script>
@endsection
