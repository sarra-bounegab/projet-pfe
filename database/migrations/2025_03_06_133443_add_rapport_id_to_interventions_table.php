<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('interventions', function (Blueprint $table) {
            $table->unsignedBigInteger('rapport_id')->nullable()->after('technicien_id');
            $table->foreign('rapport_id')->references('id')->on('rapports_techniciens')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::table('interventions', function (Blueprint $table) {
            $table->dropForeign(['rapport_id']);
            $table->dropColumn('rapport_id');
        });
    }
};
