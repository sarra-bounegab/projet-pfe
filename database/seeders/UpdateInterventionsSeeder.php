<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateInterventionsSeeder extends Seeder
{
    public function run()
    {
        // Obtenir les ids des types Logiciel et Matériel
        $logicielId = DB::table('type_interventions')->where('type', 'Logiciel')->value('id');
        $materielId = DB::table('type_interventions')->where('type', 'Matériel')->value('id');

        // Mettre à jour toutes les interventions avec un id de type correct
        DB::table('interventions')->whereNull('type_intervention_id')->update(['type_intervention_id' => $logicielId]); // Par exemple, en mettant un type par défaut, ici Logiciel
    }
}
