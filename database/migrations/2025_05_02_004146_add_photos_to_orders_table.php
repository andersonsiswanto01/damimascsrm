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
            $table->json('KTP_photo')->nullable()->after('landcertificate_photo'); // Replace 'some_column' with the correct previous column
            $table->json('kartu_keluarga')->nullable()->after('KTP_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_masters', function (Blueprint $table) {
            $table->dropColumn(['KTP_photo', 'kartu_keluarga']);
        });
    }
};
