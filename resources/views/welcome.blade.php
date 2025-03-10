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
    @endif

    <script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.72/build/spline-viewer.js"></script>

    <style>
   .content-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 4%;
    width: 90%;
  
    margin: auto;
}

.text-section {
    flex: 1;
    min-width: 40%;
    text-align: left;
}

.spline-container {
    flex: 1;
    min-width: 55%;
    display: flex;
    justify-content: center;
    align-items: center;
}

spline-viewer {
    width: 100%;
    height: 54em; 
    object-fit: contain; 
    transform: scale(1); 
}




</style>

</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation Bar  -->
        <header class="bg-white shadow-md py-4">
            <div class="max-w-screen-xl mx-auto px-6 flex justify-between items-center">
                <!-- Logo on the left -->
                <img src="{{ asset('images/Logo-Anep-Animation.png') }}" class="w-30 h-12 mr-4"  alt="Logo-a">

                <!-- Navigation  -->
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

        <!-- Content Section -->
        <div class="flex-grow flex justify-center items-center ">
            <div class="content-wrapper">
                <div class="text-section">
                    <p class="text-lg text-gray-700 font-medium mb-4">
                        Cette application est alimentée par une base de données MySQL. Nous stockons les profils des utilisateurs et les interventions techniques pour une gestion optimale et efficace.
                    </p>
                </div>
                <div class="spline-container">
                <spline-viewer loading-anim-type="spinner-small-dark" url="https://prod.spline.design/UBIXlM5VuxNGq-CF/scene.splinecode"></spline-viewer>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
