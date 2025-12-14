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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_code')->unique();
            $table->foreignId('family_id')->constrained()->onDelete('restrict');

            // Personal Details
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('date_of_birth');
            $table->string('birth_certificate_number')->nullable();
            $table->string('religion')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('photo_path')->nullable();

            // Previous Education
            $table->string('previous_school')->nullable();
            $table->string('previous_class')->nullable();
            $table->decimal('previous_gpa', 4, 2)->nullable();

            // Health Information
            $table->text('medical_conditions')->nullable();
            $table->text('allergies')->nullable();

            // Status
            $table->enum('status', ['active', 'alumni', 'dropout', 'transferred'])->default('active');
            $table->date('admission_date');
            $table->date('leaving_date')->nullable();
            $table->text('leaving_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('student_code');
            $table->index(['family_id', 'status']);
            $table->index('status');
            $table->index('full_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
