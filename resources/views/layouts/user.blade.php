<x-app-layout>
@extends('layouts.navigation')

@section('sidebar-links')

<a href="{{ route('user.statistics') }}" class="block py-2 px-4 rounded hover:bg-green-600">Mes Statistiques</a>

<a href="{{ route('user.gestionsinterventions') }}" class="block py-2 px-4 rounded hover:bg-green-600">
            Gestion interventions
        </a>

        <a href="{{ route('user.gestionsinterventions.create') }}" class="block py-2 px-4 rounded hover:bg-green-600">
            Ajouter une intervention
        </a>
@endsection
</x-app-layout>
