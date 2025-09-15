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
            $table->decimal('acc_reimbursement_amount', 10, 2)->default(0);
            $table->string('acc_reimbursement_currency', 10)->default('INR');
            $table->decimal('acc_direct_amount', 10, 2)->default(0);
            $table->string('acc_direct_currency', 10)->default('INR');
            $table->decimal('acc_total_inr', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournees', function (Blueprint $table) {
            $table->dropColumn([
                'acc_reimbursement_amount',
                'acc_reimbursement_currency',
                'acc_direct_amount',
                'acc_direct_currency',
                'acc_total_inr'
            ]);
        });
    }
};
