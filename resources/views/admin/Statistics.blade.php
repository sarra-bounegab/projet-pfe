@extends('layouts.admin')

@section('content')
    <div class="py-6 h-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Statistiques des Utilisateurs</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-green-100 p-3 rounded-lg shadow">
                        <h3 class="text-lg font-bold">Total des Utilisateurs</h3>
                        <p class="text-3xl font-semibold">{{ $totalUsers }}</p>
                    </div>

                    <div class="bg-blue-100 p-3 rounded-lg shadow">
                        <h3 class="text-lg font-bold">Total des Administrateurs</h3>
                        <p class="text-3xl font-semibold">{{ $totalAdmins }}</p>
                    </div>

                    <div class="bg-yellow-100 p-3 rounded-lg shadow">
                        <h3 class="text-lg font-bold">Total des Techniciens</h3>
                        <p class="text-3xl font-semibold">{{ $totalTechnicians }}</p>
                    </div>

                    <div class="bg-red-100 p-3 rounded-lg shadow">
                        <h3 class="text-lg font-bold">Total des Interventions</h3>
                        <p class="text-3xl font-semibold">{{ $totalInterventions }}</p>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <!-- Graphique des utilisateurs -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-lg font-bold mb-3">Répartition des Utilisateurs</h3>
                        <div class="max-h-64">
                            <canvas id="usersChart"></canvas>
                        </div>
                    </div>

                    <!-- Graphique des interventions -->
                    <div class="bg-white shadow-md p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-3">Répartition des Interventions</h3>
                        <div class="max-h-64">
                            <canvas id="interventionsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclure Chart.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof Chart === 'undefined') {
                console.error("Chart.js n'est pas chargé !");
                return;
            }

            // Graphique des utilisateurs
            var ctxUsers = document.getElementById('usersChart').getContext('2d');
            new Chart(ctxUsers, {
                type: 'bar',
                data: {
                    labels: ['Utilisateurs', 'Administrateurs', 'Techniciens'],
                    datasets: [{
                        label: 'Nombre d\'utilisateurs',
                        data: [{{ $totalUsers }}, {{ $totalAdmins }}, {{ $totalTechnicians }}],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(255, 206, 86, 0.7)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 206, 86, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2, // Rend le graphique plus large et moins haut
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Graphique des interventions
            var ctxInterventions = document.getElementById('interventionsChart').getContext('2d');
            new Chart(ctxInterventions, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($interventionLabels) !!},
                    datasets: [{
                        data: {!! json_encode($interventionData) !!},
                        backgroundColor: ['#f39c12', '#9b59b6', '#1abc9c', '#e74c3c', '#2ecc71']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2
                }
            });
        });
    </script>
@endsection
