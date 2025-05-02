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
        DB::table('type_interventions')->insert([
            'type' => 'Non spécifié',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
    
    public function down()
    {
        DB::table('type_interventions')
            ->where('type', 'Non spécifié')
            ->delete();
    }
    
};
