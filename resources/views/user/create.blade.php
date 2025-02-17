


<x-app-layout>


<div class="py-6 h-full">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Créer une nouvelle Intervention</h2>

                <!-- Formulaire -->
                <form action="{{ route('user.storeintervention') }}" method="POST">
    @csrf

    <label for="type_intervention_id">Type d'intervention :</label>
    <select name="type_intervention_id" required>
        @foreach($types as $type)
            <option value="{{ $type->id }}">{{ $type->nom }}</option>
        @endforeach
    </select>

    <label for="description">Description :</label>
    <textarea name="description" required></textarea>

    <button type="submit">Créer</button>
</form>

            </div>
        </div>
    </div>

</x-app-layout>