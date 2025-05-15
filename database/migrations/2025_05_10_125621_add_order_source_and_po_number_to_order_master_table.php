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
        Schema::table('order_masters', function (Blueprint $table) {
               $table->enum('order_source', ['whatsapp', 'meeting', 'other', 'po'])->nullable();
                $table->string('po_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_masters', function (Blueprint $table) {
               $table->dropColumn(['order_source', 'po_number']);
        });
    }
};
