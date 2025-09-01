<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsdRateToChancelleryRatesTable extends Migration
{
    public function up()
    {
        Schema::table('chancellery_rates', function (Blueprint $table) {
            $table->decimal('usd_rate', 10, 4)->nullable()->after('rate');
            $table->renameColumn('rate', 'eur_rate');
        });
    }

    public function down()
    {
        Schema::table('chancellery_rates', function (Blueprint $table) {
            $table->renameColumn('eur_rate', 'rate');
            $table->dropColumn('usd_rate');
        });
    }
}
