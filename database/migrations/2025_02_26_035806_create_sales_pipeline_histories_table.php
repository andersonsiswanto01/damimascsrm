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
    {Schema::create('sales_pipeline_history', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('sales_pipeline_id');
        $table->string('previous_status', 50)->nullable();
        $table->string('new_status', 50);
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->timestamps();
    
        // Foreign keys
        $table->foreign('sales_pipeline_id')->references('id')->on('sales_pipelines')->onDelete('cascade');
        $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_pipeline_histories');
    }
};
