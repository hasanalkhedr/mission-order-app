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
        Schema::table('tournees', function (Blueprint $table) {
            $table->boolean('actual_fees')->default(false);
            $table->decimal('actual_fees_amount',10,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournees', function (Blueprint $table) {
            $table->dropColumn('actual_fees');
            $table->dropColumn('actual_fees_amount');
        });
    }
};
