@extends('layouts.admin')

@section('content')
    <div class="py-6  h-full  ">
        <div class="">
            <div class="bg-white shadow-md sm:rounded-lg p-6">
                
                <h2 class="text-2xl font-semibold mb-4">Gestion Globale des Utilisateurs globale</h2>


                <button onclick="openModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
    + Ajouter un utilisateur
</button>




                <table class="min-w-full  table-auto border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">#</th>
                            <th class="px-4 py-2 border">Nom</th>
                            <th class="px-4 py-2 border">Email</th>
                            <th class="px-4 py-2 border">Date de création</th>
                            <th class="px-4 py-2 border">Profil</th>
                            <th class="px-4 py-2 border">Service</th>
                            <th class="px-4 py-2 border">Statut</th> 
                            <th class="px-4 py-2 border">Action</th>

                        </tr>
                    </thead>
                    <tbody>
                    @if($users->isEmpty())
    <tr>
        <td colspan="8" class="text-center py-4">Aucun utilisateur trouvé.</td>
    </tr>
@else
    @foreach($users as $user)
        <tr class="text-center hover:bg-gray-50">
            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
            <td class="border px-4 py-2">{{ $user->name }}</td>
            <td class="border px-4 py-2">{{ $user->email }}</td>
            <td class="border px-4 py-2">{{ $user->created_at->format('d/m/Y') }}</td>
            <td class="border px-4 py-2">
                @if($user->profile_id == 1)
                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded">Administrateur</span>
                @elseif($user->profile_id == 2)
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Technicien</span>
                @elseif($user->profile_id == 3)
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">Utilisateur</span>
                @else
                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded">Inconnu</span>
                @endif
            </td>
            <td class="px-2 py-2 bg-gray-100 rounded">
                {{ $user->service ? $user->service->name : 'Non attribué' }}
            </td>
            <td class="border px-4 py-2">
                @if($user->status == 1)
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Actif</span>
                @else
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded">Inactif</span>
                @endif
            </td>
            <td class="border px-4 py-2">
            <button onclick="openEditUserModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', {{ $user->status }})"
                                        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        Modifier
                                    </button>
            </td>
        </tr>
    @endforeach
@endif

                        
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>



<!-- Modal d'édition -->
<div id="editUserModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">Modifier l'utilisateur</h2>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="user_id" name="user_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium">Nom</label>
                    <input type="text" id="user_name" name="name" class="w-full border rounded p-2 bg-gray-200" disabled>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" id="user_email" name="email" class="w-full border rounded p-2 bg-gray-200" disabled>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Service</label>
                    <input type="text" id="user_service" name="service" class="w-full border rounded p-2 bg-gray-200" disabled>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Profil</label>
                    <select name="profile_id" class="w-full border rounded p-2">
                        <option value="1">Administrateur</option>
                        <option value="2">Technicien</option>
                        <option value="3">Utilisateur</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Statut</label>
                    <select id="user_status" name="status" class="w-full border rounded p-2">
                        <option value="1">Actif</option>
                        <option value="0">Inactif</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="mr-2 px-4 py-2 bg-gray-400 text-white rounded">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditUserModal(id, name, email, status) {
            document.getElementById('user_id').value = id;
            document.getElementById('user_name').value = name;
            document.getElementById('user_email').value = email;
            document.getElementById('user_status').value = status;
            document.getElementById('editUserForm').action = '/users/' + id;
            document.getElementById('editUserModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('editUserModal').classList.add('hidden');
        }
    </script>


















<!-- Fenêtre modale -->
<div id="userModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-semibold mb-4">Ajouter un nouvel utilisateur</h2>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium">Nom</label>
                <input type="text" name="name" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Mot de passe</label>
                <input type="password" name="password" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Profil</label>
                <select name="profile_id" class="w-full border rounded p-2">
                    <option value="1">Administrateur</option>
                    <option value="2">Technicien</option>
                    <option value="3">Utilisateur</option>
                </select>
            </div>


        

            <div class="mb-4">
                <label class="block text-sm font-medium">Statut</label>
                <select name="status" class="w-full border rounded p-2">
                    <option value="1">Actif</option>
                    <option value="0">Inactif</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeModal()" class="mr-2 px-4 py-2 bg-gray-400 text-white rounded">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Créer</button>
            </div>
        </form>
    </div>
</div>



<script>
    function openModal() {
        document.getElementById('userModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('userModal').classList.add('hidden');
    }
</script>































@endsection