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
        Schema::create('student_monthly_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_academic_record_id')->constrained()->onDelete('restrict');
            $table->foreignId('monthly_fee_generation_id')->constrained()->onDelete('restrict');
            $table->integer('month');
            $table->integer('year');
            $table->date('due_date');

            // Fee Calculation
            $table->decimal('gross_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);
            $table->decimal('late_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);

            // Payment Status
            $table->enum('payment_status', ['unpaid', 'paid', 'waived'])->default('unpaid');
            $table->date('payment_date')->nullable();
            $table->foreignId('payment_id')->nullable()->constrained('monthly_fee_payments')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['student_academic_record_id', 'year', 'month']);
            $table->index(['year', 'month', 'payment_status']);
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_monthly_fees');
    }
};
