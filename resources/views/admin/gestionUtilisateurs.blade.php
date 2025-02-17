@extends('layouts.admin')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Gestion des Utilisateurs et Techniciens</h2>

                <table class="min-w-full table-auto border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">#</th>
                            <th class="px-4 py-2 border">Nom</th>
                            <th class="px-4 py-2 border">Email</th>
                            <th class="px-4 py-2 border">Date de création</th>
                            <th class="px-4 py-2 border">Profil</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="text-center hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border px-4 py-2">{{ $user->name }}</td>
                                <td class="border px-4 py-2">{{ $user->email }}</td>
                                <td class="border px-4 py-2">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="border px-4 py-2">
                                    @if($user->profile_id == 2)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Technicien</span>
                                    @elseif($user->profile_id == 3)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded">Utilisateur</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded">Inconnu</span>
                                    @endif
                                </td>
                                <td class="border px-4 py-2">
                                    <a href="#" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Modifier</a>
                                    <a href="#" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 ml-2">Supprimer</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
