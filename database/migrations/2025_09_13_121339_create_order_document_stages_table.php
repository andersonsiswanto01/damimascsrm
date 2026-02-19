<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_document_stages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('code', 255);
            $table->string('color', 255)->nullable();
            $table->boolean('editable')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_document_stages');
    }
};
