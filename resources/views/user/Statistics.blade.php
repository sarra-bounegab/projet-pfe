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
            </div>
        </div>
    </div>
@endsection
