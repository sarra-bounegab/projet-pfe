<?php

// database/migrations/xxxx_xx_xx_create_interventions_historiques_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterventionsHistoriquesTable extends Migration
{
    public function up(): void
    {
        Schema::create('interventions_historiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intervention_id')->constrained()->onDelete('cascade');  // Liaison à la table interventions
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Liaison à la table users
            $table->text('action');  // Description de l'action effectuée
            $table->text('details_technicien')->nullable();  // Détails supplémentaires si nécessaire
            $table->timestamp('created_at')->useCurrent();  // Timestamp de la création de l'action
            $table->timestamp('updated_at')->useCurrent()->nullable();  // Timestamp de la dernière mise à jour
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interventions_historiques');
    }
}
