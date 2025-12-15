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
        Schema::create('student_academic_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('restrict');
            $table->foreignId('academic_year_id')->constrained()->onDelete('restrict');
            $table->foreignId('class_id')->constrained()->onDelete('restrict');
            $table->foreignId('section_id')->constrained()->onDelete('restrict');

            $table->string('roll_number');
            $table->enum('enrollment_status', ['enrolled', 'promoted', 'detained', 'withdrawn'])->default('enrolled');
            $table->date('enrollment_date');
            $table->date('promotion_date')->nullable();

            // Academic Performance (End of Year)
            $table->decimal('final_percentage', 5, 2)->nullable();
            $table->decimal('final_gpa', 4, 2)->nullable();
            $table->string('final_grade')->nullable();
            $table->integer('class_rank')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['section_id', 'roll_number'], 'student_academic_records_section_roll_unique');
            $table->unique(['student_id', 'academic_year_id'], 'student_academic_records_student_year_unique');
            $table->index(['academic_year_id', 'class_id', 'section_id'], 'student_academic_records_year_class_section_idx');
            $table->index('enrollment_status', 'student_academic_records_enrollment_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_academic_records');
    }
};
