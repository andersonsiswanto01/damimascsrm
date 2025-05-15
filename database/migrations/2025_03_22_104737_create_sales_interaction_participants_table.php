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
        Schema::create('sales_interaction_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_interaction_id')->constrained()->onDelete('cascade'); // Links to sales_interactions
            
            // Polymorphic relation (with shorter index name)
            $table->unsignedBigInteger('participant_id');
            $table->string('participant_type');
            $table->index(['participant_type', 'participant_id'], 'sip_participant_index'); // Shortened index name
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_interaction_participants');
    }
};
