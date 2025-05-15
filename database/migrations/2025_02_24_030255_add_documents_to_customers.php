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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('NPWP_photo')->nullable();
            $table->string('SP2BKS_photo')->nullable();
            $table->string('SP2BKS_id')->nullable();
            $table->date('SP2BKS_expired')->nullable();
            $table->string('KTP_photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['NPWP_photo', 'SP2BKS_photo', 'SP2BKS_id', 'SP2BKS_expired', 'KTP_photo']);
        });
    }
};
