<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('order_payments', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->foreignId('order_id')->constrained('order_masters')->onDelete('cascade');
          $table->foreignId('order_payment_stage_id')
                  ->default(1) // default to 'Verifying'
                  ->constrained('order_payment_stages');
        $table->decimal('amount', 15, 2);
        $table->foreignId('payment_type_id')->constrained('payment_types');
        $table->text('payment_proof')->nullable();
        $table->boolean('is_verified')->default(false);
        $table->foreignId('verified_by')->nullable()->constrained('users');
        $table->timestamp('verified_at')->nullable();
        $table->foreignId('revised_from')->nullable()->constrained('order_payments');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
