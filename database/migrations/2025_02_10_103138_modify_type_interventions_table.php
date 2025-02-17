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
    Schema::table('type_interventions', function (Blueprint $table) {
        
        if (Schema::hasColumn('type_interventions', 'name')) {
            $table->dropColumn('name');
        }
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
