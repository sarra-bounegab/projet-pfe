<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // Réinsertion des nouveaux services
        DB::table('services')->insert([
            ['id' => 23, 'name' => 'REGIE-ANNABA - SOUS DIRECTION', 'parent_id' => null],
            ['id' => 22, 'name' => 'REGIE-CONSTANTINE - SOUS DIRECTION', 'parent_id' => null],
            ['id' => 31, 'name' => "REGIE -D'ORAN - SOUS DIRECTION", 'parent_id' => null],
            ['id' => 1, 'name' => 'AGENCE OUARGLA - SOUS DIRECTION', 'parent_id' => null],
            ['id' => 15, 'name' => "Direction d'Unité", 'parent_id' => null],
            ['id' => 10, 'name' => 'Division Programmation et Suivi des Commandes', 'parent_id' => 15],
            ['id' => 3, 'name' => 'Service Exécution', 'parent_id' => 10],
            ['id' => 30, 'name' => 'Service Traduction', 'parent_id' => 10],
            ['id' => 4, 'name' => 'Service Programmation', 'parent_id' => 10],
            ['id' => 6, 'name' => 'Service Agence Centrale', 'parent_id' => 10],
            ['id' => 12, 'name' => 'Division Finances Comptabilité', 'parent_id' => 15],
            ['id' => 35, 'name' => 'Service Finances', 'parent_id' => 12],
            ['id' => 36, 'name' => 'Service Comptabilité', 'parent_id' => 12],
            ['id' => 13, 'name' => 'Division Commerciale', 'parent_id' => 15],
            ['id' => 2, 'name' => 'Service Facturation', 'parent_id' => 13],
            ['id' => 20, 'name' => 'Service Contrôle', 'parent_id' => 13],
            ['id' => 26, 'name' => 'Service Numérisation et Envois', 'parent_id' => 13],
            ['id' => 5, 'name' => 'Service Réclamation', 'parent_id' => 13],
            ['id' => 14, 'name' => 'Division Administration Générale', 'parent_id' => 15],
            ['id' => 18, 'name' => 'Service Moyens Généraux', 'parent_id' => 14],
            ['id' => 21, 'name' => 'Service Ressources Humaines', 'parent_id' => 14],
            ['id' => 7, 'name' => 'Service Juridique', 'parent_id' => 14],
            ['id' => 8, 'name' => 'Service Documentation', 'parent_id' => 14],
            ['id' => 17, 'name' => 'REGIE-BOUMERDES', 'parent_id' => null],
            ['id' => 19, 'name' => 'MESSAGERIE', 'parent_id' => null],
            ['id' => 24, 'name' => 'Division Recouvrement', 'parent_id' => 15],
            ['id' => 33, 'name' => 'Service Suivi des Créances', 'parent_id' => 24],
            ['id' => 34, 'name' => 'Service Gestion Portefeuille Clients', 'parent_id' => 24],
            ['id' => 27, 'name' => 'Division Etudes et Développement', 'parent_id' => 15],
            ['id' => 11, 'name' => 'Service Statistiques', 'parent_id' => 27],
            ['id' => 16, 'name' => 'Service Informatique', 'parent_id' => 27],
            ['id' => 39, 'name' => 'Service Statistique', 'parent_id' => 27],
            ['id' => 28, 'name' => 'Division BOMOP', 'parent_id' => 15],
            ['id' => 25, 'name' => 'Service Contrôle Bomop', 'parent_id' => 28],
            ['id' => 29, 'name' => 'Service Abonnement Bomop', 'parent_id' => 28],
            ['id' => 37, 'name' => 'Service Correction', 'parent_id' => 28],
            ['id' => 38, 'name' => 'Service Conception', 'parent_id' => 28],
            ['id' => 9, 'name' => 'Sécurité Interne d’Entreprise', 'parent_id' => null],
        ]);
    }


    
    public function down()
    {
        DB::table('services')->truncate();
    }
};
