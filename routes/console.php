<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


//        Schedule::command('app:payment-reminder')->weekdays()->at('08:00');
Schedule::command('app:task-approval-reminder')->wednesdays()->at('10:00');
