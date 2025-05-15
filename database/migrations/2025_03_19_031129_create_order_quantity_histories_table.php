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
        Schema::create('order_quantity_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_quantity_id')->constrained()->onDelete('cascade'); // Links to orders table
            $table->date('allocated_date'); // Date of allocation
            $table->integer('allocated_quantity'); // How much was allocated
            $table->integer('remaining_balance'); // Remaining order balance
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_quantity_histories');
    }
};
