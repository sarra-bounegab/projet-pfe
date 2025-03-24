<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('historique_interventions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('intervention_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('technicien_id')->nullable();
            $table->unsignedBigInteger('ancien_technicien_id')->nullable();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('statut');
            $table->timestamp('date_creation')->useCurrent();
            $table->timestamp('date_modification')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();

            $table->string('action');

            // Clés étrangères
            $table->foreign('intervention_id')->references('id')->on('interventions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('technicien_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('ancien_technicien_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('historique_interventions');
    }
};
