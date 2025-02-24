@extends('layouts.admin')

@section('content')
    <div class="py-6 h-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Statistiques des Utilisateurs</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Statistiques générales -->
                    <div class="bg-green-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-bold">Total des Utilisateurs</h3>
                        <p class="text-3xl font-semibold">{{ $totalUsers }}</p>
                    </div>

                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-bold">Total des Administrateurs</h3>
                        <p class="text-3xl font-semibold">{{ $totalAdmins }}</p>
                    </div>

                    <div class="bg-yellow-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-bold">Total des Techniciens</h3>
                        <p class="text-3xl font-semibold">{{ $totalTechnicians }}</p>
                    </div>

                    <div class="bg-red-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-bold">Total des Interventions</h3>
                        <p class="text-3xl font-semibold">{{ $totalInterventions }}</p>
                    </div>
                </div>

                <!-- Graphique -->
                <div class="bg-white p-6 rounded-lg shadow mt-6">
                    <h3 class="text-lg font-bold mb-4">Répartition des Utilisateurs</h3>
                    <canvas id="usersChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclure Chart.js via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('usersChart').getContext('2d');
            var usersChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Utilisateurs', 'Administrateurs', 'Techniciens'],
                    datasets: [{
                        label: 'Nombre d\'utilisateurs',
                        data: [{{ $totalUsers }}, {{ $totalAdmins }}, {{ $totalTechnicians }}],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(255, 206, 86, 0.5)'
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
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
