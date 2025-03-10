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
        Schema::create('rapports_techniciens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('intervention_id');
            $table->text('contenu');
            $table->timestamps();
    
            // Clé étrangère vers interventions
            $table->foreign('intervention_id')->references('id')->on('interventions')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapports_techniciens');
    }
};
