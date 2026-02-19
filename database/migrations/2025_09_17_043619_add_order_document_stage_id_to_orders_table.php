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
        Schema::table('orders', function (Blueprint $table) {
           $table->foreignId('order_document_stage_id')
                ->default(1) // default stage ID = 1
                ->constrained('order_document_stages')
                ->cascadeOnDelete(); // if stage is deleted, delete related orders (or change to restrictOnDelete if you prefer)

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['order_document_stage_id']);
            $table->dropColumn('order_document_stage_id');
        });
    }
};
