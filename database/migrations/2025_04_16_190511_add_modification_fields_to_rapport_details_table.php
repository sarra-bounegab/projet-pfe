<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('rapport_details', function (Blueprint $table) {
        $table->text('contenu_precedent')->nullable()->after('contenu');
        $table->datetime('date_derniere_modification')->nullable()->after('contenu_precedent');
        $table->unsignedBigInteger('user_derniere_modification')->nullable()->after('date_derniere_modification');

        $table->foreign('user_derniere_modification')->references('id')->on('users');
    });
}

public function down(): void
{
    Schema::table('rapport_details', function (Blueprint $table) {
        $table->dropForeign(['user_derniere_modification']);
        $table->dropColumn(['contenu_precedent', 'date_derniere_modification', 'user_derniere_modification']);
    });
}

};
