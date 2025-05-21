<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://unpkg.com/ionicons@5.5.2/dist/ionicons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <!-- Bootstrap 5 DataTables CSS  -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    {{-- Bootstrap Icons (facultatif mais utile) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- Bootstrap 5 DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <!-- Initialisation DataTables -->
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            });
        });
    </script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="flex">
        <!-- Sidebar fixée -->
        <nav class="fixed left-0 top-0 h-full w-64 bg-white text-grey flex flex-col shadow-xl">
            <div class="p-8 text-center">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/Logo-Anep-Animation.png') }}" class="w-30 h-12 mx-auto" alt="Logo">
                </a>
            </div>
            
            <ul class="flex-grow p-4 space-y-2">
                @if(auth()->user()->profile_id == 1 || auth()->user()->profile_id == 4) 
                    <li>
                        <a href="{{ route('statistics') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                            <i class="fa-solid fa-chart-simple mr-2"></i>
                            Statistique
                        </a>
                    </li> 
                    <li>
                        <a href="{{ route('admin.gestionsGlobale') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                            <i class="fa-solid fa-users mr-2"></i> 
                            Gestion Utilisateurs
                        </a>
                  

                    <li>
                        <a href="{{ route('admin.gestionsinterventions') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                            <i class="fa-solid fa-tools mr-2"></i>
                            Gestion Interventions    

                        </a>
                    </li>
                   <li>
    <a href="{{ route('interventions.index', ['status' => 'termine']) }}" class="block px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
        <i class="fa-solid fa-clock-rotate-left mr-2"></i>
        Historique
    </a>
</li>

                @elseif(auth()->user()->profile_id == 2)  
                    <li>
                        <a href="{{ route('statistics') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                            <i class="fa-solid fa-chart-simple mr-2"></i>
                            Statistique
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('technician.interventions') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                            <i class="fa-solid fa-tools mr-2"></i>
                            Gestion Interventions  
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.gestionsinterventions') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                            <i class="fa-solid fa-tools mr-2"></i> 
                            Mes interventions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('interventions.historique') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                            <i class="fa-solid fa-clock-rotate-left mr-2"></i>
                           Historiques interventions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.gestionsinterventions.create') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                            <i class="fa-solid fa-circle-plus mr-2"></i>
                            Ajouter Intervention
                        </a>
                    </li>
                @elseif(auth()->user()->profile_id == 3)
                    <li>
                        <a href="{{ route('statistics') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                            <i class="fa-solid fa-chart-simple mr-2"></i> 
                            Statistique
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.gestionsinterventions') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                            <i class="fa-solid fa-tools mr-2"></i> 
                            Mes interventions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('historique') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                            <i class="fa-solid fa-clock-rotate-left mr-2"></i> 
                            Historiques interventions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.gestionsinterventions.create') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                            <i class="fa-solid fa-circle-plus mr-2"></i>
                            Ajouter Intervention
                        </a>
                    </li>
                @endif
            </ul>
            
            <!-- User profile section in sidebar -->
@auth
    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-user text-gray-500"></i>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                </div>
            </div>

            <div>
                <x-dropdown align="right" width="48" class="z-50">
                    <x-slot name="trigger">
                        <button
                            type="button"
                            class="inline-flex items-center text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6l-3-3m0 0l3-3m-3 3h12" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Profile -->
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                href="#"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                            >
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
@endauth

        </nav>

        <!-- Contenu Principal -->
        <main class="flex-1 ml-64 p-4">
            @isset($header)
                <header class="bg-white shadow p-3">
                    <div class="container">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <div class="container mt-4">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>