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
        Schema::create('sales_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Lead", "Proposal", "Negotiation", "Closed"
            $table->integer('order')->default(0); // Helps define the sequence of stages
            $table->boolean('is_final')->default(false); // Marks if this is an end-stage (e.g., "Closed Won" or "Closed Lost")
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_stages');
    }
};
