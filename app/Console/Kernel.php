<?php

namespace App\Console;

use Cache;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Http\Controllers\Wechat\Robot\Gift;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

        //
        // ->everyMinute(); ->dailyAt('7:00');
        $schedule->call(function () {
            // DB::table('recent_users')->delete();
           $gift = new Gift;
           $gift->daily();
        })->dailyAt('16:00');
    }
}
