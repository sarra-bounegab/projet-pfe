<?php
// database/migrations/create_notifications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_notifications', function (Blueprint $table) { // Changed from 'notifications'
            $table->id();
            $table->unsignedBigInteger('user_id'); // Destinataire
            $table->unsignedBigInteger('intervention_id')->nullable(); // Intervention concernée
            $table->unsignedBigInteger('sender_id')->nullable(); // Expéditeur (qui a fait l'action)
            $table->string('type'); // Type de notification
            $table->string('title'); // Titre de la notification
            $table->text('message'); // Message de la notification
            $table->json('data')->nullable(); // Données additionnelles
            $table->boolean('is_read')->default(false); // Lu ou non
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('intervention_id')->references('id')->on('interventions')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');

            $table->index(['user_id', 'is_read']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_notifications'); // Changed from 'notifications'
    }
};
