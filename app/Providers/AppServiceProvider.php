<?php

namespace App\Providers;
use App\Models\Rapport;
use Illuminate\Support\ServiceProvider;
use App\Models\Intervention;
use App\Models\InterventionHistorique;
use App\Observers\InterventionObserver;
use App\Models\DetailsIntervention;
use App\Observers\DetailsInterventionObserver;


class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
{
   
    Intervention::observe(InterventionObserver::class);
    
    
     DetailsIntervention::observe(DetailsInterventionObserver::class);
}

    public function register()
    {
        //
    }
}
