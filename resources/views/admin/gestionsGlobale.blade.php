@extends('layouts.app')

@section('content')
    <div class="py-6  h-full  ">
        <div class="">
            <div class="bg-white shadow-md sm:rounded-lg p-6">
                
                <h2 class="text-2xl font-semibold mb-4">Gestion Globale des Utilisateurs globale</h2>

                <button onclick="openModal()" class="px-4 py-2 bg-green-500 text-white rounded">Ajouter un utilisateur</button>




<table id="userTable" class="min-w-full table-auto border-collapse border border-gray-300">

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
            <td>{{ $user->service?->parentService?->name ?? $user->service?->name ?? 'No Parent' }}</td>


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



    <div id="editUserModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-4">Modifier l'utilisateur</h2>
        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_user_id" name="id">

            <label class="block text-sm font-medium">Nom</label>
            <input type="text" id="edit_user_name" name="name" class="border p-2 w-full mb-2" required>

            <label class="block text-sm font-medium">Email</label>
            <input type="email" id="edit_user_email" name="email" class="border p-2 w-full mb-2" required>

            <label class="block text-sm font-medium">Statut</label>
            <select id="edit_user_status" name="status" class="border p-2 w-full mb-2">
                <option value="1">Actif</option>
                <option value="0">Inactif</option>
            </select>

            <label class="block text-sm font-medium">Profil</label>
            <select id="edit_user_profile" name="profile_id" class="border p-2 w-full mb-4">
                <option value="1">Administrateur</option>
                <option value="2">Technicien</option>
                <option value="3">Employé</option>
            </select>

            <div class="flex justify-end">
                <button type="button" onclick="closeEditUserModal()" class="mr-2 text-gray-600">Annuler</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Modifier</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('editUserForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Empêche le rechargement de la page

        let form = this;
        let formData = new FormData(form);
        let userId = document.getElementById('edit_user_id').value;

        fetch(`/users/${userId}`, {
            method: 'POST', // Laravel attend PUT, donc on ajoute un _method
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert('Utilisateur mis à jour avec succès !');
            closeEditUserModal();
            location.reload(); // Rafraîchir la liste des utilisateurs
        })
        .catch(error => console.error('Erreur :', error));
    });

    function openEditUserModal(id, name, email, status, profile_id) {
        document.getElementById('edit_user_id').value = id;
        document.getElementById('edit_user_name').value = name;
        document.getElementById('edit_user_email').value = email;
        document.getElementById('edit_user_status').value = status;
        document.getElementById('edit_user_profile').value = profile_id;

        document.getElementById('editUserModal').classList.remove('hidden');
    }

    function closeEditUserModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }
</script>














<script>
    $(document).ready(function () {
        $('#userTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/French.json"
            },
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "responsive": true
        });
    });
</script>

@endsection 