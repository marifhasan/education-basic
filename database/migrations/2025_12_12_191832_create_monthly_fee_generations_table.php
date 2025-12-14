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
        Schema::create('monthly_fee_generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->onDelete('restrict');
            $table->foreignId('class_id')->nullable()->constrained()->onDelete('restrict');
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('restrict');
            $table->integer('month');
            $table->integer('year');
            $table->date('due_date');
            $table->integer('students_count')->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->foreignId('generated_by')->constrained('users')->onDelete('restrict');
            $table->timestamp('generated_at');
            $table->timestamps();

            $table->index(['academic_year_id', 'year', 'month']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_fee_generations');
    }
};
