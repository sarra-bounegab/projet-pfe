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
<<<<<<< HEAD
    public function boot()
    {
        Intervention::observe(InterventionObserver::class); // Enregistrement de l'observateur
    }
public function register()
{
    $this->app->bind(NotificationService::class, function ($app) {
        return new NotificationService();
    });
}
=======
    public function boot(): void
{
   
    Intervention::observe(InterventionObserver::class);
    
    
     DetailsIntervention::observe(DetailsInterventionObserver::class);
}

    public function register()
    {
        //
    }
>>>>>>> 9cb757b780aed92b354e86f514ea09f8392649e2
}
