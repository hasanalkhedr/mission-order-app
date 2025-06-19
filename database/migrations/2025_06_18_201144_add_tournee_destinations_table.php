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
        Schema::create('tournee_destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournee_id')->constrained('tournees')->cascadeOnDelete();
            $table->string('departure_location');
            $table->string('arrive_location');
            $table->date('start_date');
            $table->time('start_time');
            $table->date('end_date');
            $table->time('end_time');
            $table->timestamps();
        });
        Schema::table('tournees', function (Blueprint $table) {
            $table->dropColumn('departure_location');
            $table->dropColumn('arrive_location');
            $table->dropColumn('start_date');
            $table->dropColumn('start_time');
            $table->dropColumn('end_date');
            $table->dropColumn('end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournees', function (Blueprint $table) {
            $table->string('departure_location');
            $table->string('arrive_location');
            $table->date('start_date');
            $table->time('start_time');
            $table->date('end_date');
            $table->time('end_time');
        });
        Schema::dropIfExists('tournee_destinations');

    }
};
