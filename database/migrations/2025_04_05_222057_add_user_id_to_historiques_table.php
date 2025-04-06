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
        Schema::table('historiques', function (Blueprint $table) {
            // Ajout de la colonne 'user_id'
            $table->unsignedBigInteger('user_id');

            // Ajouter une clé étrangère pour 'user_id'
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historiques', function (Blueprint $table) {
            // Supprimer la colonne 'user_id' si la migration est annulée
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
