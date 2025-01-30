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
        Schema::create('lpds', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 50)->unique();
            $table->date('date');
            $table->string('origin', 20); // project code
            $table->string('destination', 20); // project code
            $table->string('attention_person', 50)->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->dateTime('received_at')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users');
            $table->date('received_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('lpds');
        Schema::enableForeignKeyConstraints();
    }
};
