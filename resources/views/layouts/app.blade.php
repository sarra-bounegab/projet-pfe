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



    <!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<!-- Bootstrap 5 DataTables CSS (si besoin) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- Bootstrap 5 DataTables JS (si besoin) -->
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Initialisation DataTables -->
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            responsive: true,
            dom: 'Bfrtip', /
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'] 
            
        });
    });
</script>

    <div class="flex">
        <!-- Sidebar fixée -->
        <nav class="fixed left-0 top-0 h-full w-64 bg-gray-900 text-white flex flex-col shadow-lg">
        <div class="p-8 text-center">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('images/Logo-Anep-Animation.png') }}" class="w-30 h-12 mx-auto" alt="Logo">
            </a>
        </div>
            <ul class="flex-grow p-4 space-y-2">
            
                @if(auth()->user()->profile_id == 1) 
                    <li>
                        <a href="{{ route('statistics') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                             Statistiques
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.gestionsGlobale') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                             Gestion Utilisateurs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.gestionsinterventions') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                             Gestion Interventions
                        </a>
                    </li>
                @elseif(auth()->user()->profile_id == 2) 
                    <li>
                        <a href="{{ route('technician.interventions') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                             Gestion Interventions  
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.gestionsinterventions') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                             Mes interventions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.gestionsinterventions.create') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                             Ajouter Intervention
                        </a>
                    </li>
                @elseif(auth()->user()->profile_id == 3) 
                    <li>
                        <a href="{{ route('user.statistics') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                             Mes Statistiques
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.gestionsinterventions') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                             Mes interventions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.gestionsinterventions.create') }}" class="block px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                             Ajouter Intervention
                        </a>
                    </li>
                @endif
            </ul>
            @auth
                <div class="p-4 border-t border-gray-700">
                    <span class="text-sm">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-700">🚪 Déconnexion</button>
                    </form>
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
