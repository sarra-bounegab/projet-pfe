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
    Schema::create('rapport_details', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('intervention_id');
        $table->unsignedBigInteger('user_id');
        $table->text('contenu');
        $table->string('status');
        $table->datetime('modification_date'); // Date précise de la modification
        $table->timestamps(); // created_at et updated_at
        
        $table->foreign('intervention_id')->references('id')->on('interventions');
        $table->foreign('user_id')->references('id')->on('users');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapport_details');
    }
};
