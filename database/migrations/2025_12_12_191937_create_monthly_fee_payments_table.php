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
        Schema::create('monthly_fee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_monthly_fee_id')->constrained()->onDelete('restrict');
            $table->string('receipt_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'mobile_banking', 'card']);
            $table->date('payment_date');
            $table->string('transaction_reference')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('collected_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();

            $table->index('receipt_number');
            $table->index('payment_date');
            $table->index('collected_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_fee_payments');
    }
};
