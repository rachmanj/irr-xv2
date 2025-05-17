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
        Schema::create('document_distributions', function (Blueprint $table) {
            $table->id();
            $table->string('document_type'); // 'Invoice' or 'AdditionalDocument'
            $table->unsignedBigInteger('document_id'); // ID of the document
            $table->string('from_location_code')->nullable(); // Source location code
            $table->string('to_location_code'); // Destination location code
            $table->unsignedBigInteger('sender_id')->nullable(); // User who sent the document
            $table->unsignedBigInteger('receiver_id')->nullable(); // User who received the document
            $table->dateTime('sent_at')->nullable(); // When the document was sent
            $table->dateTime('received_at')->nullable(); // When the document was received
            $table->string('status')->default('pending'); // pending, in_transit, received, rejected
            $table->text('remarks')->nullable(); // Additional notes
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('receiver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_distributions');
    }
}; 