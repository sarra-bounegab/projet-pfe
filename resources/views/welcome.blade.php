<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>ANEP Plateforme</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Curseur ligne verte */
        .typewriter::after {
            content: '';
            display: inline-block;
            width: 4px;
            height: 1.5em;
            background-color:rgb(0, 211, 78); /* Vert ANEP */
            margin-left: 3px;
            animation: blink 0.7s infinite;
            vertical-align: middle;
        }

        /* Effet clignotant */
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Navbar -->
    <header class="bg-white shadow-md py-4">
        <div class="max-w-screen-xl mx-auto px-6 flex justify-between items-center">
            <!-- Logo -->
            <img src="{{ asset('images/Logo-Anep-Animation.png') }}" class="w-32 h-12 object-contain" alt="Logo ANEP">

            <!-- Navigation -->
            @if (Route::has('login'))
                <nav class="flex space-x-6">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-lg font-semibold px-4 py-2 text-black hover:text-white hover:bg-green-500 rounded-md transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-lg font-semibold px-4 py-2 text-black hover:text-white hover:bg-green-500 rounded-md transition">
                            Connexion
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="text-lg font-semibold px-4 py-2 text-black hover:text-white hover:bg-green-500 rounded-md transition">
                                S'inscrire
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <!-- Contenu Principal -->
    <main class="flex-1 flex items-center justify-center p-10">
        <div class="flex flex-wrap md:flex-nowrap w-full max-w-7xl items-center gap-10">
            
            <!-- Texte animé -->
            <div class="w-full md:w-1/2 pr-0 md:pr-6 text-center md:text-left space-y-6">
                <h1 class="text-5xl font-extrabold text-green-600 drop-shadow-md">ANEP</h1>
                <p id="typing-text" class="typewriter text-2xl leading-relaxed text-gray-800 font-medium overflow-hidden break-words h-32"></p>

            </div>

          
            <div class="w-full md:w-1/2 flex items-center justify-center">
                
                <img src="{{ asset('images/Digital pass@1-512x512 (2).png') }}" 
                     alt="Badge ANEP" 
                     class="max-w-xs md:max-w-sm rounded-xl shadow-xl">
            </div>
        </div>
    </main>

    
    
    <script>
        const text = "Bienvenue sur la plateforme officielle de gestion des interventions de l'ANEP. " 
            

        let index = 0;
        const speed = 65; // Vitesse 

        function typeWriter() {
            const container = document.getElementById("typing-text");
            if (index < text.length) {
                let currentChar = text.charAt(index);
                container.innerHTML += currentChar;
                index++;
                setTimeout(typeWriter, speed);
            }
        }

        
        window.onload = typeWriter;
    </script>

</body>

</html>
