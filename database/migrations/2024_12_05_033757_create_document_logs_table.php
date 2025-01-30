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
        Schema::create('document_logs', function (Blueprint $table) {
            $table->id();
            $table->string('model'); // The model name (e.g., 'Invoice', 'Envelope')
            $table->unsignedBigInteger('record_id')->nullable(); // The ID of the record in the model
            $table->string('type'); // The type of action (e.g., 'created', 'updated', 'deleted')
            $table->text('remarks')->nullable(); // Additional remarks or comments
            $table->foreignId('user_id')->constrained('users'); // The user who performed the action
            $table->integer('points')->nullable(); // The points associated with the action
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_logs');
    }
};
