<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedBigInteger('parent_id')->nullable(); // Pour gérer les sous-services
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('services')->onDelete('cascade');
        });

        // Insertion des services et sous-services
        DB::table('services')->insert([
            ['id' => 1, 'name' => 'Service RH', 'parent_id' => null],
            ['id' => 2, 'name' => 'Comercial', 'parent_id' => null],
            ['id' => 3, 'name' => 'Contrôle', 'parent_id' => 2], 
            ['id' => 4, 'name' => 'Numérisation', 'parent_id' => 2], 
            ['id' => 5, 'name' => 'Service Juriste', 'parent_id' => null],
            ['id' => 6, 'name' => 'Recouvrement', 'parent_id' => null],
            ['id' => 7, 'name' => 'Comptabilité', 'parent_id' => null],
            ['id' => 8, 'name' => 'Finance', 'parent_id' => null],
            ['id' => 9, 'name' => 'BOMP', 'parent_id' => null],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};
