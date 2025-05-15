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
        Schema::create('sales_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_pipeline_id')->constrained('sales_pipelines')->onDelete('cascade');
            $table->enum('interaction_type', ['call', 'meeting', 'email', 'whatsapp', 'sms', 'other']);
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->dateTime('interaction_date');
            $table->dateTime('follow_up_date')->nullable();
            $table->text('summary')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_interactions');
    }
};
