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
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('admission_number')->unique();
            $table->foreignId('academic_year_id')->constrained()->onDelete('restrict');
            $table->foreignId('class_id')->constrained()->onDelete('restrict');
            $table->foreignId('family_id')->constrained()->onDelete('restrict');

            // Applicant Information
            $table->string('applicant_first_name');
            $table->string('applicant_last_name');
            $table->enum('applicant_gender', ['male', 'female', 'other']);
            $table->date('applicant_dob');
            $table->string('applicant_photo_path')->nullable();

            // Application Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'admitted'])->default('pending');
            $table->date('application_date');
            $table->date('approval_date')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('rejection_reason')->nullable();

            // Fee Information
            $table->decimal('admission_fee_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');

            // Created Student Reference
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['academic_year_id', 'status']);
            $table->index('admission_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
