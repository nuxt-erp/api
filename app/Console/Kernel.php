<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    protected function getTimeZoneDifference()
    {
        $firstTime  = \DB::select("SELECT current_timestamp() as mysql_date");
        $firstTime  = strtotime($firstTime[0]->mysql_date);
        $lastTime   = strtotime(now());
        $timeDiff   = (($lastTime-$firstTime) /60/60);
        return abs($timeDiff);

    }
    protected function schedule(Schedule $schedule)
    {
        $schedule->call('App\Http\Controllers\Sales\SaleController@importShopify')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
