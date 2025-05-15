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
        Schema::create('sales_interaction_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_interaction_id')
                ->constrained('sales_interactions')
                ->onDelete('cascade'); // Deletes follow-ups when interaction is deleted
            $table->date('followup_date');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_interaction_followups');
    }
};
