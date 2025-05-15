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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('order_stage_id')->default(1) // Set default value to 1
                  ->constrained('order_stages') // Link to order_stages table
                  ->onDelete('cascade'); // Cascade delete on related order_stage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['order_stage_id']);
            $table->dropColumn('order_stage_id');
        });
    }
};
