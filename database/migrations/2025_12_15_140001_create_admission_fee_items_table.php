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
        Schema::create('admission_fee_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_fee_configuration_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->decimal('amount', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['admission_fee_configuration_id', 'is_active'], 'admission_fee_items_config_active_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_fee_items');
    }
};