<?php

namespace App\Console;

use App\Models\Company;
use App\Models\Config;
use App\Models\CronLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

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

        $schedule->call(function () {

            $companies = Company::all();

            foreach ($companies as $company) {
                config(['database.connections.tenant.schema' => $company->schema]);
                DB::reconnect('tenant');
                DB::transaction(function () {

                    $config = Config::find(1);
                    if (!empty($config) && !empty($config->shopify_sync_sales) && $config->shopify_sync_sales === true) {
                        $api = resolve('Shopify\API');
                        $api->syncOrders();
                    }
                });

                # code...
            }

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
        })->everyMinute()
            ->name('shopify_sales')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
