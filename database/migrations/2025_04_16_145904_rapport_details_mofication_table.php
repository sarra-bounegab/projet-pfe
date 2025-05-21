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
        Schema::table('rapport_details', function (Blueprint $table) {
            // Ajout de la colonne 'user_id'
            $table->unsignedBigInteger('rapport_id'); 

            // Ajouter une clé étrangère pour 'user_id'
          
        $table->foreign('rapport_id')->references('id')->on('rapports');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapport_details_mofication');
    }
};
