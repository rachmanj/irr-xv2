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
        Schema::create('lpd_additional_document', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lpd_id');
            $table->foreignId('additional_document_id')->constrained('additional_documents')->onDelete('cascade');
            $table->unique(['lpd_id', 'additional_document_id']); // Ensure unique combination of lpd_id and additional_document_id
            $table->timestamps();

            $table->foreign('lpd_id')
                ->references('id')
                ->on('lpds')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lpd_additional_document');
    }
};
