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
        Schema::table('order_masters', function (Blueprint $table) {
            $table->date('payment_date')->nullable();
            $table->json('payment_picture')->nullable(); // for storing multiple image filenames/paths
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_masters', function (Blueprint $table) {
            $table->dropColumn(['payment_date', 'payment_picture']);
        });
    }
};
