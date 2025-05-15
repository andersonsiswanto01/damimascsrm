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
        Schema::create('sp2bks_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sp2bks_id')->constrained('corporate_sp2bks')->onDelete('cascade');
            $table->foreignId('produsenbenihproduct_id')
            ->constrained('produsen_benih_product') // ✅ Exact table name
            ->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sp2bks_products');
    }
};
