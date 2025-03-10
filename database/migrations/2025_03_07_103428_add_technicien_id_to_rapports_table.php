<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('rapports', function (Blueprint $table) {
        if (!Schema::hasColumn('rapports', 'technicien_id')) {
            $table->foreignId('technicien_id')->nullable()->constrained('users')->onDelete('set null');
        }
    });
}

    
    public function down()
    {
        Schema::table('rapports', function (Blueprint $table) {
            $table->dropForeign(['technicien_id']);
            $table->dropColumn('technicien_id');
        });
    }
    
};
