<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id(); // Primary key 'id'
            $table->string('description'); // Profile description (e.g., administrator, technician, user)
            $table->timestamps();
        });

        // Create the 'users' table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('status')->default(0); 
            $table->unsignedBigInteger('profile_id')->after('status'); 
            
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade'); 
            $table->rememberToken();
            $table->timestamps();
        });

        // Create 'password_reset_tokens' table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Create 'sessions' table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });



       


        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop 'sessions' table
        Schema::dropIfExists('sessions');

        // Drop 'password_reset_tokens' table
        Schema::dropIfExists('password_reset_tokens');

        // Drop 'users' table
        Schema::dropIfExists('users');

        // Drop 'profiles' table
        Schema::dropIfExists('profiles');
    }
};
