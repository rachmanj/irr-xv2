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
        Schema::create('spis', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('origin', 20); // project code
            $table->string('destination', 20); // project code
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('received_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spis');
    }
};
