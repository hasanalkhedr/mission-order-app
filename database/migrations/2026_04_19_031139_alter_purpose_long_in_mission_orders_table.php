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
            $table->string('purpose',500)->change();
        });
        Schema::table('tournees', function (Blueprint $table) {
            $table->string('purpose',500)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mission_orders', function (Blueprint $table) {
            $table->string('purpose',255)->change();
        });
        Schema::table('tournees', function (Blueprint $table) {
            $table->string('purpose',255)->change();
        });
    }
};
