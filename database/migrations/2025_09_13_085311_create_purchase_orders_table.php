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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id(); // PK
            $table->unsignedInteger('sequence_number'); // running number
            $table->unsignedTinyInteger('month'); // 1-12
            $table->unsignedSmallInteger('year'); // e.g. 2025
            $table->string('code')->unique(); // e.g. 0001/SUS/09/2025
            $table->date('date'); // order date
            $table->text('note')->nullable(); // remarks
            $table->timestamps();

            // Ensure sequence_number is unique for each month/year
            $table->unique(['sequence_number', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
   public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
