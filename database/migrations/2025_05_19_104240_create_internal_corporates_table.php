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
        Schema::create('internal_corporates', function (Blueprint $table) {
            $table->id();
            $table->string('psm'); // e.g. '1', '2', '6A'
            $table->foreignId('province_id')->constrained()->cascadeOnDelete();
            $table->foreignId('internal_corporate_type_id')->constrained('internal_corporate_types')->onDelete('restrict');
            $table->string('initial', 10); // Up to 10 characters
            $table->string('legal_form'); // e.g. 'PT', 'CV', 'UD'
            $table->string('pt_name'); // Full corporate name
            $table->string('name'); // Full address
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_corporates');
    }
};
