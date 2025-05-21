<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->string('transport_type')->nullable();
            $table->string('transport_details', 255)->nullable();
            $table->string('meal_location', 255)->nullable();
            $table->integer('meal_participants')->nullable();
        });
    }

    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['type', 'transport_type', 'transport_details', 'meal_location', 'meal_participants']);
        });
    }
};
