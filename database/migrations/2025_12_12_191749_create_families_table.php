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
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->string('family_code')->unique();

            // Father Information
            $table->string('father_name');
            $table->string('father_nid')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('father_occupation')->nullable();
            $table->decimal('father_income', 12, 2)->nullable();

            // Mother Information
            $table->string('mother_name');
            $table->string('mother_nid')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('mother_occupation')->nullable();

            // Guardian (if different)
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relation')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_nid')->nullable();

            // Contact & Address
            $table->string('primary_phone');
            $table->string('secondary_phone')->nullable();
            $table->string('email')->nullable();
            $table->text('present_address');
            $table->text('permanent_address')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('primary_phone');
            $table->index('family_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families');
    }
};
