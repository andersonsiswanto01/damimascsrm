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
        Schema::table('order_stages', function (Blueprint $table) {
              $table->boolean('edittable')->default(false)->after('is_final'); // Replace 'your_column_name' with an existing column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_stages', function (Blueprint $table) {
           $table->dropColumn('edittable');
        });
    }
};
