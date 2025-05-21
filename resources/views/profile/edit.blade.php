@extends('layouts.app')

@section('content')

    <section class="max-w-xl mx-auto mt-10 p-6 bg-white rounded shadow">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Informations du profil</h2>
            <p class="mt-1 text-sm text-gray-600">Mettez à jour votre nom, email, et changez votre mot de passe.</p>
        </header>

        <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('PATCH')

            <div>
                <label for="name" class="block font-medium text-sm text-gray-700">Nom</label>
                <input id="name" name="name" type="text" class="mt-1 block w-full border-gray-300 rounded" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                <input id="email" name="email" type="email" class="mt-1 block w-full border-gray-300 rounded" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ancien mot de passe -->
            <div>
                <label for="current_password" class="block font-medium text-sm text-gray-700">Mot de passe actuel</label>
                <input id="current_password" name="current_password" type="password" class="mt-1 block w-full border-gray-300 rounded" autocomplete="current-password" />
                @error('current_password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nouveau mot de passe -->
            <div>
                <label for="password" class="block font-medium text-sm text-gray-700">Nouveau mot de passe</label>
                <input id="password" name="password" type="password" class="mt-1 block w-full border-gray-300 rounded" autocomplete="new-password" />
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmation mot de passe -->
            <div>
                <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirmer le nouveau mot de passe</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border-gray-300 rounded" autocomplete="new-password" />
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Enregistrer</button>

                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600"
                    >Modifications enregistrées.</p>
                @endif
            </div>
        </form>
    </section>
@endsection
