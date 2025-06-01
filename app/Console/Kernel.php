<?php

namespace App\Console;

use App\Console\Commands\AFTokenCommand;
use App\Jobs\AudioJob;
use App\Jobs\GeocodeJob;
use App\Jobs\GoogleSyncJob;
use App\Jobs\MarketPlaceJob;
use App\Jobs\TransitJob;
use App\Jobs\MpesaJob;
use App\Jobs\OrderAssignJob;
use App\Jobs\ReminderJob;
use App\Jobs\RoutingJob;
use App\Jobs\StockJob;
use App\Jobs\UnattendedJob;
use App\Jobs\WoocommerceJob;
use App\Jobs\WooOrdersJob;
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
        // \App\Console\Commands\ShopifyCommand::class,
        \App\Console\Commands\GoogleSheetCommand::class,
        \App\Console\Commands\GoogleSheetUpdate::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->job(new OrderAssignJob())
        //     ->everyFiveMinutes()
        //     ->timezone('Africa/Nairobi')
        //     ->between('8:00', '20:00')->days([1, 2, 3, 4, 5, 6]);

        $schedule->command('boxleo:assign')
            ->everyFiveMinutes()
            ->timezone('Africa/Nairobi')
            ->between('6:00', '23:00')->days([1, 2, 3, 4, 5, 6]);



        $schedule->job(new AudioJob())
            ->everyTenMinutes()
            ->timezone('Africa/Nairobi')
            ->between('21:15', '02:15');

        $schedule->job(new StockJob())
            ->timezone('Africa/Nairobi')
            ->twiceDaily(21, 6);


        $schedule->job(new MpesaJob())
            ->timezone('Africa/Nairobi')
            ->everyTenMinutes()
            ->between('20:15', '23:15');

        // $schedule->job(new RoutingJob())
        //     ->everyTenMinutes()
        //     ->timezone('Africa/Nairobi')
        //     ->between('8:00', '20:00')->days([1, 2, 3, 4, 5, 6]);


        $schedule->command('google:update')
            ->everyTwoHours()
            ->timezone('Africa/Nairobi')
            ->between('10:00', '20:00')->days([1, 2, 3, 4, 5, 6]);


        $schedule->command('googleold:update')
            ->daily()
            ->timezone('Africa/Nairobi')
            ->days([1, 2, 3, 4, 5, 6]);

        // $schedule->command('google:update')->timezone('Africa/Nairobi')->dailyAt('21:40');
        // $schedule->command('telescope:prune')->everyTwoHours();

        $schedule->command('google:zone')
            ->twiceDailyAt(13, 18, 40)
            ->timezone('Africa/Nairobi')
            ->days([1, 2, 3, 4, 5, 6]);

        $schedule->job(new ReminderJob())->timezone('Africa/Nairobi')->dailyAt('12:00')->days([1, 2, 3, 4, 5, 6]);
        $schedule->job(new UnattendedJob())->timezone('Africa/Nairobi')->dailyAt('23:00')->days([1, 2, 3, 4, 5, 6]);
        $schedule->job(new TransitJob())->timezone('Africa/Nairobi')->dailyAt('17:00')->days([1, 2, 3, 4, 5, 6]);

        $schedule->job(new AFTokenCommand())->twiceDaily(8, 20)->days([1, 2, 3, 4, 5, 6]);

        // $schedule->job(new MarketPlaceJob())->everyThirtyMinutes()->days([1, 2, 3, 4, 5, 6]);


        $schedule->command('boxleo:command')
            ->twiceDaily(7, 16)
            ->timezone('Africa/Nairobi')
            ->days([1, 2, 3, 4, 5, 6]);


        // $schedule->command('boxleo:command')->timezone('Africa/Nairobi')->dailyAt('17:00')->days([1, 2, 3, 4, 5, 6]);
        // $schedule->job(new WooOrdersJob())->hourly()->days([1, 2, 3, 4, 5, 6]);

        // $schedule->job(new ExpireCommand())->dailyAt('04:30');
        $schedule->command('backup:database')->timezone('Africa/Nairobi')->twiceDaily(2, 14);
        $schedule->command('backup:tidy')->timezone('Africa/Nairobi')->dailyAt('02:30');
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
