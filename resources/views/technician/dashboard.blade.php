@extends('layouts.technicien')  <!-- Referring to the admin layout with sidebar -->

@section('content')

    <div class="h-full" >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8  shadow-md h-full">
            <div class="bg-white  overflow-hidden  sm:rounded-lg ">
                <div class="flex">



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
