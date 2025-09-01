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
        Schema::table('mission_orders', function (Blueprint $table) {
            $table->decimal('expense_reimbursement_total', 12, 2)->default(0);
            $table->decimal('expense_direct_total', 12, 2)->default(0)->after('expense_reimbursement_total');
            $table->decimal('expense_grand_total', 12, 2)->default(0)->after('expense_direct_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mission_orders', function (Blueprint $table) {
            $table->dropColumn([
                'expense_reimbursement_total',
                'expense_direct_total',
                'expense_grand_total'
            ]);
        });
    }
};
