<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->string('id', 8)->primary(); // Customer ID as VARCHAR(8)
            $table->string('customer_name');
            $table->string('company_name')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('npwp')->nullable();
            $table->string('nik_ktp')->nullable();
            $table->date('registration_date');
            $table->text('address');
            $table->enum('status', ['corporate', 'private', 'breeder']);
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('sub_district_id');
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('province_id');
            $table->string('telephone_number');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
