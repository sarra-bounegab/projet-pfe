<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    @endif
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation Bar at the top -->
        <header class="bg-white shadow-md py-4">
            <div class="max-w-screen-xl mx-auto px-4 flex justify-between items-center">
                <!-- Logo on the left -->
                <img src="{{ asset('images/Logo-Anep-Animation.png') }}" class="w-30 h-12 mr-4"  alt="Logo-a">

                <!-- Navigation for login/register on the right -->
                @if (Route::has('login'))
                    <nav class="flex space-x-6">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-xl font-semibold px-4 py-2 text-black transition duration-300 hover:text-white hover:bg-green-500 rounded-md ring-1 ring-transparent focus:outline-none focus-visible:ring-[#FF2D20]">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-xl font-semibold px-4 py-2 text-black transition duration-300 hover:text-white hover:bg-green-500 rounded-md ring-1 ring-transparent focus:outline-none focus-visible:ring-[#FF2D20]">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-xl font-semibold px-4 py-2 text-black transition duration-300 hover:text-white hover:bg-green-500 rounded-md ring-1 ring-transparent focus:outline-none focus-visible:ring-[#FF2D20]">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <!-- Content Section in the middle, taking available space -->
        <div class="flex-grow flex justify-center items-center px-4 py-16">
            <div class="text-center max-w-2xl">
                <p class="text-lg text-gray-700 font-medium mb-4">
                Cette application est alimentée par une base de données MySQL. Nous stockons les profils des utilisateurs et les interventions techniques pour une gestion optimale et efficace.
                </p>
            </div>
        </div>

        <!-- Footer at the bottom 
        <footer class="bg-gray-800 text-white py-6 mt-auto">
            <div class="max-w-screen-xl mx-auto px-4">
                <div class="flex justify-between">
                    <div>
                        <h3 class="text-xl font-bold">Contact Us</h3>
                        <p class="mt-2">Email: contact@anep.com</p>
                        <p>Phone: +123 456 7890</p>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">Follow Us</h3>
                        <div class="mt-2 space-x-4">
                            <a href="#" class="text-green-400 hover:text-green-600 transition duration-300">Facebook</a>
                            <a href="#" class="text-green-400 hover:text-green-600 transition duration-300">Twitter</a>
                            <a href="#" class="text-green-400 hover:text-green-600 transition duration-300">Instagram</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>-->
    </div>
</body>
</html>

