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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->integer('qty');
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->date('delivery_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_photo')->nullable();
            $table->string('landcertificate_photo')->nullable();
            $table->string('statementletter_photo')->nullable();
            
            // Enum status column
            $table->enum('status', [
                'pending',        // Order placed but not processed
                'awaiting_payment', // Waiting for customer payment
                'processing',     // Order being prepared
                'shipped',        // Order shipped but not delivered
                'delayed',        // Order delayed 
                'completed',      // Order completed
                'canceled',       // Order canceled
            ])->default('pending');

            // Location fields
            $table->foreignId('province_id')->constrained()->cascadeOnDelete();
            $table->foreignId('regency_id')->constrained()->cascadeOnDelete();
            $table->foreignId('district_id')->constrained()->cascadeOnDelete();
            $table->foreignId('village_id')->constrained()->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
