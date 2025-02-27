<x-app-layout>
@extends('layouts.navigation')

@section('sidebar-links')

    <a href="{{ route('statistics') }}" class="block py-2 px-4  rounded hover:bg-green-600">Statistiques</a>

    <a  href="{{ route('admin.gestionsGlobale') }}" class="block py-2 px-4  rounded hover:bg-green-600">
        Gestion Utilisateurs Globale</a>
    <a  href="{{ route('admin.gestionsinterventions') }}" class="block py-2 px-4  rounded hover:bg-green-600">
        Gestion des interventions</a>


@endsection
</x-app-layout>


