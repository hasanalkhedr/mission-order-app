<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chancellery_rates', function (Blueprint $table) {
            $table->id();
            $table->decimal('rate', 10, 4); // Conversion rate (e.g., 1 USD = X Local)
            $table->date('month_year'); // Stores just year-month (e.g., 2023-11-01)
            $table->enum('status', ['draft', 'approved']);
            $table->timestamps();

            $table->unique(['month_year']); // Only one rate per month
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chancellery_rates');
    }
};
