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
        Schema::table('rapports', function (Blueprint $table) {
            $table->text('contenu')->nullable(); // tu peux le mettre nullable si tu veux autoriser vide
        });
    }
    
    public function down()
    {
        Schema::table('rapports', function (Blueprint $table) {
            $table->dropColumn('contenu');
        });
    }
    
};
