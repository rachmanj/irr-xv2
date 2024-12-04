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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->date('receive_date');
            $table->date('payment_date')->nullable();
            $table->foreignId('supplier_id');
            $table->foreignId('supplier_branch');
            $table->string('receive_project', 30)->nullable(); // project lokasi invoice diterima
            $table->string('invoice_project', 30)->nullable(); // project atas beban
            $table->string('payment_project', 30)->nullable(); // project for responsible do payment
            $table->string('currency', 3)->default('IDR');
            $table->string('status', 20)->default('pending'); // pending / return / sap
            $table->foreignId('created_by');
            $table->integer('duration')->nullable();
            $table->string('flag', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
