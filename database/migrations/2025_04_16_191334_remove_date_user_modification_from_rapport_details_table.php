<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rapport_details', function (Blueprint $table) {
            // Supprimer la clé étrangère avant de supprimer la colonne
            $table->dropForeign(['user_derniere_modification']);

            // Ensuite, supprimer la colonne
            $table->dropColumn(['date_derniere_modification', 'user_derniere_modification']);
        });
    }

    public function down(): void
    {
        Schema::table('rapport_details', function (Blueprint $table) {
            $table->datetime('date_derniere_modification')->nullable();
            $table->unsignedBigInteger('user_derniere_modification')->nullable();

            // Recréer la clé étrangère si tu veux la restaurer dans un rollback
            $table->foreign('user_derniere_modification')->references('id')->on('users');
        });
    }
};
