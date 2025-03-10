<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('interventions', function (Blueprint $table) {
            $table->string('titre')->after('id'); // Ajout du champ titre après l'ID
        });
    }

    public function down() {
        Schema::table('interventions', function (Blueprint $table) {
            $table->dropColumn('titre');
        });
    }
};
