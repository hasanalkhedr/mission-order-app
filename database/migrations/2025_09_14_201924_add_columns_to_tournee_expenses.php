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
        Schema::table('tournee_expenses', function (Blueprint $table) {
            // Add new columns for reimbursement and direct amounts
            $table->decimal('reimbursement_amount', 10, 2)->default(0)->after('amount');
            $table->string('reimbursement_currency', 10)->default('INR')->after('reimbursement_amount');
            $table->decimal('direct_amount', 10, 2)->default(0)->after('reimbursement_currency');
            $table->string('direct_currency', 10)->default('INR')->after('direct_amount');
            $table->decimal('total_inr', 10, 2)->default(0)->after('direct_currency');

            // Optional: Make the original amount and currency nullable for backward compatibility
            $table->decimal('amount', 8, 2)->nullable()->change();
            $table->string('currency', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournee_expenses', function (Blueprint $table) {
            $table->dropColumn([
                'reimbursement_amount',
                'reimbursement_currency',
                'direct_amount',
                'direct_currency',
                'total_inr'
            ]);

            // Restore original columns to not null if they were changed
            $table->decimal('amount', 8, 2)->nullable(false)->change();
            $table->string('currency', 255)->nullable(false)->change();
        });
    }
};
