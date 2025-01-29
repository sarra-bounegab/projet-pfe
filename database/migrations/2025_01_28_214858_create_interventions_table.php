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
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

       
        Schema::create('interventions', function (Blueprint $table) {
            $table->id(); // L'ID de l'intervention
            $table->dateTime('date'); // La date de l'intervention
            $table->timestamps(); // Optionnel, si vous souhaitez ajouter les champs created_at et updated_at
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
