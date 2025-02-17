<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeInterventionsSeeder extends Seeder
{
    public function run()
    {
        // Assurer que la table est vide avant de réinsérer
        DB::table('type_interventions')->truncate();

        // Ajouter uniquement Logiciel et Matériel
        DB::table('type_interventions')->insert([
            ['type' => 'Logiciel'],
            ['type' => 'Matériel'],
        ]);
    }
}
