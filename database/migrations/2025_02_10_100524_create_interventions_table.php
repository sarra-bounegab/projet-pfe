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
        if (!Schema::hasTable('interventions')) {
            Schema::create('interventions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('type_intervention_id')->constrained()->onDelete('cascade');
                $table->text('description');
                $table->date('date');
                $table->string('status');
                $table->timestamps();
            });
        }
    }
    
    public function down()
    {
        Schema::dropIfExists('interventions');
    }


    
};
