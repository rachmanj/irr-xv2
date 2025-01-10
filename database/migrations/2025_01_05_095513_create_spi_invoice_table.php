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
        Schema::create('spi_invoice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spi_id');
            $table->unsignedBigInteger('invoice_id');
            $table->timestamps();

            $table->foreign('spi_id')
                ->references('id')
                ->on('spis')
                ->onDelete('cascade');

            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spi_invoice');
    }
};
