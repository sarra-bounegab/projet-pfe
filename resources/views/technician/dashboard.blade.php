<x-app-layout>

    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex">
                    <!-- Sidebar -->
                    <div class=" bg-gray-800 text-white p-6">
                        <h3 class="text-xl mb-4">technicien Menu</h3>
                        <ul>
                            <li class="mb-4">
                                <a href="" class="text-white">Gestion des interventions</a>
                            </li>
                            
                        </ul>

                        <!-- Formulaire de déconnexion -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>

                  
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
