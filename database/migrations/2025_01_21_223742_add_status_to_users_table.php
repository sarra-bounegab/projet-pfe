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
        // Add the 'status' column to the 'users' table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('status')->default(1)->after('usertype'); // Add the 'status' column after the 'usertype' column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the 'status' column from the 'users' table if the migration is rolled back
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
