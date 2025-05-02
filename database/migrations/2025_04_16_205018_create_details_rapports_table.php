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
        Schema::create('details_rapports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapport_id')->constrained()->cascadeOnDelete();
            $table->foreignId('intervention_id')->constrained('interventions');
            $table->foreignId('technicien_id')->constrained('users');
            $table->text('contenu');
            $table->foreignId('user_id')->constrained()->comment('Qui a fait la modification');
            $table->string('action')->default('modification');
            $table->timestamps();
            
            // Index pour les performances
            $table->index('rapport_id');
            $table->index('intervention_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_rapports');
    }
};
