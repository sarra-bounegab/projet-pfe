@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Interventions Statistics</h1>

        <!-- Formulaire de filtrage -->
        <form action="{{ route('statistics') }}" method="GET" class="mb-4">
            <div class="form-group">
                <label for="period">Select Period:</label>
                <select name="period" id="period" class="form-control">
                    <option value="7days" {{ $periodFilter == '7days' ? 'selected' : '' }}>Last 7 days</option>
                    <option value="month" {{ $periodFilter == 'month' ? 'selected' : '' }}>Last Month</option>
                    <option value="year" {{ $periodFilter == 'year' ? 'selected' : '' }}>Last Year</option>
                </select>
            </div>

            <div class="form-group">
                <label for="service">Select Service:</label>
                <select name="service" id="service" class="form-control">
                    <option value="">All Services</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ request('service') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Filter</button>
        </form>

        <!-- Affichage du graphique -->
        <h3>Interventions by Service</h3>
        <canvas id="interventionChart" width="400" height="200"></canvas>

        <script>
            var ctx = document.getElementById('interventionChart').getContext('2d');
            var interventionChart = new Chart(ctx, {
                type: 'bar', // Type du graphique
                data: {
                    labels: @json($serviceNames), // Noms des services sur l'axe X
                    datasets: [{
                        label: 'Interventions Count', // Légende du graphique
                        data: @json($interventionCounts), // Comptes des interventions pour chaque service
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Couleur des barres
                        borderColor: 'rgba(54, 162, 235, 1)', // Bordure des barres
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Services' // Titre de l'axe X
                            }
                        },
                        y: {
                            beginAtZero: true, // L'axe Y commence à zéro
                            title: {
                                display: true,
                                text: 'Number of Interventions' // Titre de l'axe Y
                            }
                        }
                    }
                }
            });
        </script>
    </div>
@endsection
