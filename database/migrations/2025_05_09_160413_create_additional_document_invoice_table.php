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
        Schema::create('additional_document_invoice', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('additional_document_id')->constrained('additional_documents')->onDelete('cascade');
            $table->string('remarks')->nullable();
            $table->timestamps();
            
            // Prevent duplicate combinations with a shorter index name
            $table->unique(['invoice_id', 'additional_document_id'], 'doc_invoice_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('additional_document_invoice');
        Schema::enableForeignKeyConstraints();
    }
};
