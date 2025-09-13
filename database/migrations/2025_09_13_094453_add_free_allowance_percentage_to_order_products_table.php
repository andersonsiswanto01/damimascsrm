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
        Schema::table('order_products', function (Blueprint $table) {
            // decimal(8,2) means max 99,999,999.99 → good for percentage as decimal
            $table->decimal('free_allowance_percentage', 8, 2)->default(0)->after('free_allowance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn('free_allowance_percentage');
        });
    }
};
