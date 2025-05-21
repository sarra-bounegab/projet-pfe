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
        // 1. Supprimer la contrainte de clé étrangère
        $table->dropForeign('details_interventions_admin_id_foreign');
        
        // 2. Supprimer la colonne
        $table->dropColumn('admin_id');
    });
}

public function down()
{
    Schema::table('details_interventions', function (Blueprint $table) {
        // Recréer la colonne et la contrainte en cas de rollback
        $table->unsignedBigInteger('admin_id')->nullable();

        $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    
};
