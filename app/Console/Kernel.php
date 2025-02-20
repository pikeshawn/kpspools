<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\RunOneTimeScript;
use App\Console\Commands\HolidayBilling;
use App\Console\Commands\PaymentReminder;
use App\Console\Commands\PaymentHistory;
use App\Console\Commands\SendServiceFeeNotification;
use App\Console\Commands\DetermineFilterCleans;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        RunOneTimeScript::class,
        PaymentReminder::class,
        PaymentHistory::class,
        HolidayBilling::class,
        DetermineFilterCleans::class,
        SendServiceFeeNotification::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
//        $schedule->command('app:payment-reminder')->weekdays()->at('08:00');
        $schedule->command('app:task-approval-reminder')->wednesdays()->at('10:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
