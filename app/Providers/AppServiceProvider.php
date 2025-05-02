<?php

namespace App\Providers;
use App\Models\Rapport;
use Illuminate\Support\ServiceProvider;
use App\Models\Intervention;
use App\Observers\InterventionObserver;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
{
   
    Intervention::observe(InterventionObserver::class);
    
    
    Rapport::observe(\App\Observers\RapportObserver::class);
}

    public function register()
    {
        //
    }
}
