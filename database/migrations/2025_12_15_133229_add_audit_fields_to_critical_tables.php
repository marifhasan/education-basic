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
        $tables = [
            'academic_years',
            'curricula',
            'classes',
            'sections',
            'fee_items',
            'class_fee_structures',
            'discount_types',
            'students',
            'families',
            'student_academic_records',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade')->after('created_at');
                $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade')->after('updated_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'academic_years',
            'curricula',
            'classes',
            'sections',
            'fee_items',
            'class_fee_structures',
            'discount_types',
            'students',
            'families',
            'student_academic_records',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropForeign(['updated_by']);
                $table->dropColumn(['created_by', 'updated_by']);
            });
        }
    }
};
