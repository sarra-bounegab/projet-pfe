@extends('layouts.admin')

@section('content')
    <div class="py-6 h-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Statistiques Globales</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Nombre total d'utilisateurs -->
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-bold">Total Utilisateurs</h3>
                        <p class="text-3xl font-semibold">{{ $totalUsers }}</p>
                    </div>

                    <!-- Nombre total d'administrateurs -->
                    <div class="bg-red-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-bold">Administrateurs</h3>
                        <p class="text-3xl font-semibold">{{ $totalAdmins }}</p>
                    </div>

                    <!-- Nombre total de techniciens -->
                    <div class="bg-green-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-bold">Techniciens</h3>
                        <p class="text-3xl font-semibold">{{ $totalTechnicians }}</p>
                    </div>

                    <!-- Nombre total d'interventions -->
                    <div class="bg-yellow-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-bold">Interventions</h3>
                        <p class="text-3xl font-semibold">{{ $totalInterventions }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
