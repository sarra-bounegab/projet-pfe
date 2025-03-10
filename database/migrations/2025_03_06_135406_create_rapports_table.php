<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rapports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intervention_id')->constrained()->onDelete('cascade');
            $table->text('contenu');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rapports');
    }
};
