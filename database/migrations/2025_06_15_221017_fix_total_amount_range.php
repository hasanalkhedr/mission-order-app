<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('mission_orders', function (Blueprint $table) {
            $table->decimal('total_amount', 15, 2)->default(0)->change();
            $table->decimal('advance', 15, 2)->default(0)->change();
        });
        Schema::table('tournees', function (Blueprint $table) {
            $table->decimal('total_amount', 15, 2)->default(0)->change();
            $table->decimal('advance', 15, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('mission_orders', function (Blueprint $table) {
            $table->decimal('total_amount', 8, 2)->default(0)->change();
            $table->decimal('advance', 8, 2)->default(0)->change();
        });
        Schema::table('tournees', function (Blueprint $table) {
            $table->decimal('total_amount', 8, 2)->default(0)->change();
            $table->decimal('advance', 8, 2)->default(0)->change();
        });
    }
};
