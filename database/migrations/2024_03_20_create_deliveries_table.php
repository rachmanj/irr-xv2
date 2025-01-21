<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->date('date');
            $table->date('sent_date')->nullable();
            $table->date('received_date')->nullable();
            $table->string('origin', 20); // project code
            $table->string('destination', 20); // project code
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('received_by')->nullable()->constrained('users');
            $table->string('attention_person');
            $table->string('type', 30); // SPI or LPD
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('delivery_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained()->onDelete('cascade'); // Reference to deliveries
            $table->morphs('documentable'); // This will create documentable_id and documentable_type
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_documents');
        Schema::dropIfExists('deliveries');
    }
};
