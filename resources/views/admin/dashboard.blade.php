@extends('layouts.admin')

@section('content')

<a href="{{ route('interventions.pdf') }}" class="btn btn-primary">Télécharger Interventions PDF</a>
<a href="{{ route('users.pdf') }}" class="btn btn-success">Télécharger Utilisateurs PDF</a>
<a href="{{ route('statistics.pdf') }}" class="btn btn-info">Télécharger Statistiques PDF</a>


    <div class="h-full" >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8  shadow-md h-full">
            <div class="bg-white  overflow-hidden  sm:rounded-lg ">
                <div class="flex">



                        <!-- Formulaire de déconnexion -->
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



