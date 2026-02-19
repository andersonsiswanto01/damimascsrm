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
        Schema::create('order_payment_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // e.g., Verifying, Approved, Rejected
            $table->string('code')->unique();
            $table->string('color')->nullable(); // For UI badge
            $table->boolean('is_editable')->default(0); // Can admin manually change?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payment_stages');
    }
};
