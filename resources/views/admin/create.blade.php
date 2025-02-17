@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-semibold mb-6">Ajouter un Technicien</h2>

        <!-- Formulaire de création de techniciennnnnnnnn -->
        <form action="{{ route('admin.technicians.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('name') }}" required>
                @error('name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('email') }}" required>
                @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" id="status" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    <option value="active" @if(old('status') == 'active') selected @endif>Actif</option>
                    <option value="inactive" @if(old('status') == 'inactive') selected @endif>Inactif</option>
                </select>
                @error('status') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Créer le Technicien</button>
            </div>
        </form>
    </div>
@endsection
