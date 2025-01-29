@extends('layouts.admin')  <!-- Referring to the admin layout with sidebar -->

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex">
                    <!-- Sidebar -->
                    <div class=" bg-gray-800 text-white p-6">
                        <h3 class="text-xl mb-4">Admin Menu</h3>
                    
                        <div class="admin-sidebar">
                            
        <ul>
       
            <li><a href="{{ route('admin.gestionUsers') }}">Gestion des Utilisateurs</a></li>
            <li><a href="{{ route('admin.gestionTechnicians') }}">Gestion des Techniciens</a></li>
        </ul>
    </div>


                        <!-- Formulaire de déconnexion -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>

                  
                </div>
            </div>
        </div>
    </div>


    @endsection

