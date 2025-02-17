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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-dark antialiased">

    
    <header class="bg-white shadow-md py-4">
            <div class="max-w-screen-xl mx-auto px-4 flex justify-between items-center">
                <!-- Logo on the left -->
                <img src="{{ asset('images/Logo-Anep-Animation.png') }}" class="w-30 h-12 mr-4" alt="Logo-a">

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





        <div class="min-h-screen flex flex-col sm:justify-center items-center  sm:pt-0 bg-white ">
            <div>
                
                <img src="{{ asset('images/Logo-Anep-Animation.png') }}" class="w-30 h-12 mr-4"   href="/"alt="Logo-a">
                </a>
            </div>

           

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
