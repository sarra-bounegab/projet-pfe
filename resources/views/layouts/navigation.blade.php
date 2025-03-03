<div class="flex  w-screen h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 text-white h-screen flex flex-col ">
        
        <div class="p-8  text-center  ">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('images/Logo-Anep-Animation.png') }}" class="w-30 h-12 mr-4" alt="Logo">
            </a>
        </div>

        <!-- Contenu dynamique selon role avoir !!!! -->
        <div class="flex-1 overflow-y-auto p-4">
            @yield('sidebar-links')
        </div>

        <!-- Profil et Déconnexion en bas loste -->
        <div class="p-4 border-t">
            <div class="flex items-center justify-between">
                <div>{{ Auth::user()->name }}</div>
                 <!-- don't fog size apres a la fin !!! -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="text-white focus:outline-none">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1   overflow-y-auto">
        <div class=""><!-- content a cote du side bar  --> 
            
            <div class="">
                @yield('content')
            </div>
        </div>
    </div>
</div>


