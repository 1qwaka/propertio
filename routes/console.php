<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Carbon\CarbonInterval;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('jopa', function () {

    $interval = CarbonInterval::minutes(60);
    echo $interval, "\n";
    echo $interval->toPeriod('2024-12-07', '2024-12-08'), "\n";
    print_r($interval->toArray());

    $interval = $interval->toPeriod('2024-12-07', '2024-12-08')->toArray();
    $col = collect($interval);

    echo $col, "\n";

});

