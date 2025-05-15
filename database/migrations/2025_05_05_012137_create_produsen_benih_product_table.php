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
        Schema::create('produsen_benih_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produsen_benih_id')->constrained('produsen_benih')->onDelete('cascade');
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produsen_benih_product');
    }
};
