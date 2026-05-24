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
        Schema::create('temporary_certificate', function (Blueprint $table) {
             // Custom string ID (NOT auto increment)
            $table->string('id')->primary();

            $table->string('name');
            $table->string('segment'); // Petani / Penangkar
            $table->string('date');    // 1/30/2025 (as requested)
            $table->string('village');
            $table->string('district');
            $table->string('city');
            $table->string('province');
            $table->string('product');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_certificate');
    }
};
