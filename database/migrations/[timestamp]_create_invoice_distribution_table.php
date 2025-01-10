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
        Schema::create('invoice_distribution', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('distribution_id')->constrained('distributions')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->timestamps();

            // Optional: Add unique constraint to prevent duplicate entries
            $table->unique(['invoice_id', 'distribution_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_distribution');
    }
};
