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
        Schema::create('student_monthly_fee_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_monthly_fee_id')->constrained()->onDelete('cascade');
            $table->foreignId('discount_type_id')->constrained()->onDelete('restrict');
            $table->decimal('discount_value', 8, 2);
            $table->decimal('calculated_amount', 10, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('student_monthly_fee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_monthly_fee_discounts');
    }
};
