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
        Schema::create('sales_pipelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('lead_prospects')->onDelete('set null');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('current_stage_id')->constrained('sales_stages')->onDelete('cascade'); 
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // Salesperson
            $table->decimal('deal_value', 15, 2)->nullable(); // Deal value (should be added before using 'after' on notes)
            $table->text('notes')->nullable(); // Stores additional information
            $table->enum('status', ['open', 'in_progress', 'won', 'lost'])->default('open'); // Tracks deal progress
            $table->date('expected_close')->nullable(); // Expected close date of the deal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_pipelines');
    }
};
