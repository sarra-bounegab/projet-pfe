<x-app-layout>
    @extends('layouts.navigation')

    @section('sidebar-links')
        <a href="{{ route('technician.interventions') }}" 
           class="block py-2 px-4 rounded hover:bg-green-600">
            Gestion des interventions
        </a>
    @endsection
</x-app-layout>
