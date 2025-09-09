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
        Schema::create('document_responses', function (Blueprint $table) {
            $table->id();

            // Link to orders table
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Document type: ktp, skt, sp, etc.
            $table->string('data_type');

            // Google processor ID
            $table->string('processor_id')->nullable();

            // Full AI response and extracted map
            $table->json('response')->nullable();
            $table->json('entity_map')->nullable();

            // Extracted structured fields
            $table->string('extracted_name')->nullable();
            $table->string('extracted_nik')->nullable();
            $table->text('extracted_address')->nullable();
            $table->text('extracted_land_address')->nullable();
            $table->string('extracted_title')->nullable();
            $table->string('extracted_origin_skt')->nullable();
            $table->string('extracted_land_size')->nullable();
            $table->string('extracted_qty')->nullable();
            $table->string('extracted_religion')->nullable();
            $table->string('extracted_gender')->nullable();
            $table->string('extracted_job')->nullable();
            $table->string('extracted_marriage_status')->nullable();
            $table->string('extracted_signature')->nullable();
            $table->date('extracted_birth')->nullable();
            $table->date('extracted_date')->nullable();
            $table->string('extracted_birth_place')->nullable();
            
            // Optional metadata
            $table->float('confidence_score')->nullable();
            $table->boolean('verified')->default(false);
            $table->string('source_file')->nullable();
            $table->uuid('batch_id')->nullable();

            // Error
            $table->text('error_message')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_responses');
    }
};
