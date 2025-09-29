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
            $table->boolean('has_weekend')->default(false);
        });
         Schema::table('tournees', function (Blueprint $table) {
            $table->boolean('has_weekend')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mission_orders', function (Blueprint $table) {
            $table->dropColumn('has_weekend');
        });
        Schema::table('tournees', function (Blueprint $table) {
            $table->dropColumn('has_weekend');
        });
    }
};
