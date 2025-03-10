@extends('layouts.user')

@section('content')
<div class="py-6 h-full">
    <div class="bg-white shadow-md sm:rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-center mb-4 text-gray-800">Ajouter une Intervention</h1>

        <form action="{{ route('user.storeintervention') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Type d'intervention -->
            <div>
            <label for="titre">Titre :</label>
<input type="text" name="titre" id="titre" required class="border rounded px-4 py-2 w-full">


                <label for="type_intervention_id" class="block text-sm font-medium text-gray-700">Type d'intervention</label>
                <select name="type_intervention_id" id="type_intervention_id" required
                    class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Sélectionnez un type</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}">{{ $type->type }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" required
                    class="w-full mt-1 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none h-24"></textarea>
            </div>

            <!-- Bouton -->
            <div class="text-center">
                <button type="submit"
                    class="px-6 py-2  border hover:bg-green-600 text-dark font-semibold rounded-lg  transition">
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
