<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('historique_attributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intervention_id')->constrained()->onDelete('cascade'); // Liaison avec la table interventions
            $table->foreignId('attribue_par')->nullable()->constrained('users')->onDelete('set null'); // Qui a attribué ?
            $table->timestamp('date_attribution')->useCurrent(); // Date et heure de l'attribution
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('historique_attributions');
    }
};

