<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Intervention;
use App\Observers\InterventionObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Intervention::observe(InterventionObserver::class); // Enregistrement de l'observateur
    }

    public function register()
    {
        //
    }
}
