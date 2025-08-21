<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
Schedule::command('notifications:send-daily --role=supervisor')->dailyAt('9:00');
Schedule::command('notifications:send-daily --role=controller')->dailyAt('9:00');
Schedule::command('notifications:send-daily --role=sg')->dailyAt('10:00');
//Schedule::command('notifications:send-daily --role=director')->dailyAt('11:00');
