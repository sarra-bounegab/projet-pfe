<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('details_interventions', function (Blueprint $table) {
            $table->unsignedBigInteger('type_intervention_id')->after('technicien_id'); // ou à un autre endroit selon l'ordre souhaité
    
            // Ajoute la contrainte de clé étrangère
            $table->foreign('type_intervention_id')
                  ->references('id')
                  ->on('type_interventions')
                  ->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('details_interventions', function (Blueprint $table) {
            $table->dropForeign(['type_intervention_id']);
            $table->dropColumn('type_intervention_id');
        });
    }
    
};
