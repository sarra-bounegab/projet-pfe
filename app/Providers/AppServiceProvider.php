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

    public function boot()
    {
        Intervention::observe(InterventionObserver::class); // Enregistrement de l'observateur
        DetailsIntervention::observe(DetailsInterventionObserver::class);
}

public function register()
{
    $this->app->bind(NotificationService::class, function ($app) {
        return new NotificationService();
    });
}}
