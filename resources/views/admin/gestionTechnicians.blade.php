@extends('layouts.admin')

@section('content')
    <!-- Table with Tailwind CSS -->
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-separate border-spacing-0">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">#</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nom</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Date de création</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th> <!-- Nouvelle colonne pour le statut -->
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
                @foreach($technicians as $technician)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 border-t border-gray-200">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 border-t border-gray-200">{{ $technician->name }}</td>
                        <td class="px-6 py-4 border-t border-gray-200">{{ $technician->email }}</td>
                        <td class="px-6 py-4 border-t border-gray-200">{{ $technician->created_at->format('d/m/Y') }}</td>

                        <!-- Nouvelle colonne Status -->
                        <td class="px-6 py-4 border-t border-gray-200">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full 
                            @if($technician->status == 'active') bg-green-100 text-green-800 @elseif($technician->status == 'inactive') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($technician->status) }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 border-t border-gray-200">
                            <a href="#" class="inline-flex items-center px-4 py-2 text-white bg-blue-500 hover:bg-blue-600 rounded-md text-sm">
                                <i class="fas fa-edit mr-2"></i> Modifier
                            </a>
                            <a href="#" class="inline-flex items-center px-4 py-2 text-white bg-red-500 hover:bg-red-600 rounded-md text-sm ml-2">
                                <i class="fas fa-trash mr-2"></i> Supprimer
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Ajout de Font Awesome pour les icônes -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection

