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
        Schema::create('itos', function (Blueprint $table) {
            $table->id();
            $table->string('ito_number', 50);
            $table->date('ito_date');
            $table->string('po_number', 50);
            $table->string('vendor_code', 50);
            $table->string('vendor_name');
            $table->string('project');
            $table->foreignId('additional_doc_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itos');
    }
};
