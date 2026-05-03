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
            $table->boolean('needs_document')->default(0)->after('reject_comment');
            $table->string('autre_document')->nullable()->after('needs_document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mission_orders', function (Blueprint $table) {
            $table->dropColumn(['needs_document', 'autre_document']);
        });
    }
};
