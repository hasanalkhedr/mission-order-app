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
        Schema::table('expenses', function (Blueprint $table) {
            $table->boolean('passenger')->default(false);
            $table->boolean('distance')->default(false);
            $table->boolean('material')->default(false);
            $table->boolean('visits')->default(false);
        });
        Schema::table('tournee_expenses', function (Blueprint $table) {
            $table->boolean('passenger')->default(false);
            $table->boolean('distance')->default(false);
            $table->boolean('material')->default(false);
            $table->boolean('visits')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('passenger');
            $table->dropColumn('distance');
            $table->dropColumn('material');
            $table->dropColumn('visits');
        });
        Schema::table('tournee_expenses', function (Blueprint $table) {
            $table->dropColumn('passenger');
            $table->dropColumn('distance');
            $table->dropColumn('material');
            $table->dropColumn('visits');
        });
    }
};
