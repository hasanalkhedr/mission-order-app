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
            $table->string('endMission_location')->nullable();
            $table->time('start_time2')->nullable();
            $table->time('end_time2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mission_orders', function (Blueprint $table) {
            $table->dropColumn('endMission_location');
            $table->dropColumn('start_time2');
            $table->dropColumn('end_time2');
        });
    }
};
