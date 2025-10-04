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
            $table->integer('accountant_id')->nullable();
        });
        Schema::table('tournees', function (Blueprint $table) {
            $table->integer('accountant_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mission_orders', function (Blueprint $table) {
            $table->dropColumn('accountant_id');
        });
        Schema::table('tournees', function (Blueprint $table) {
            $table->dropColumn('accountant_id');
        });
    }
};
