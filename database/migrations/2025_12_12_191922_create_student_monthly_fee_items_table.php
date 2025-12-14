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
        Schema::create('student_monthly_fee_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_monthly_fee_id')->constrained()->onDelete('cascade');
            $table->foreignId('fee_item_id')->constrained()->onDelete('restrict');
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            $table->index('student_monthly_fee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_monthly_fee_items');
    }
};
