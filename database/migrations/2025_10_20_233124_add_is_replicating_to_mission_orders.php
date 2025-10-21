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
            $table->boolean('is_replicating')->default(false);
            $table->string('reject_comment')->nullable();
        });
        Schema::table('tournees', function (Blueprint $table) {
            $table->boolean('is_replicating')->default(false);
            $table->string('reject_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mission_orders', function (Blueprint $table) {
            $table->dropColumn('is_replicating');
            $table->dropColumn('reject_comment');
        });
        Schema::table('tournees', function (Blueprint $table) {
            $table->dropColumn('is_replicating');
            $table->dropColumn('reject_comment');
        });
    }
};
