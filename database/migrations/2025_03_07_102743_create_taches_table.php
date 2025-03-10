<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('taches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapport_id')->constrained('rapports')->onDelete('cascade');
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('taches');
    }
};
