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

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // $schedule->call(function () {
        //     $api    = resolve('Dear\API');
        //     $user = User::where('email', 'ILIKE', '%dear%')->first();

        //     $result = $api->syncProds();
        //     Import::create([
        //         'name'      => Import::DEAR_SYNC_PRODUCTS,
        //         'author_id' => $user->id,
        //         'rows'      => $result,
        //         'status'    => 'cron_update'
        //     ]);

        //     $result = $api->syncRecipes();
        //     Import::create([
        //         'name'      => Import::DEAR_SYNC_RECIPE,
        //         'author_id' => $user->id,
        //         'rows'      => $result,
        //         'status'    => 'cron_update'
        //     ]);

        // })->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
