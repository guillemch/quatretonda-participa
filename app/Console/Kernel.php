<?php

namespace App\Console;

use App\Edition;
use Illuminate\Support\Facades\Schema;
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
        Commands\AddQuestions::class,
        Commands\AddOptions::class,
        Commands\NewEdition::class,
        Commands\PublishEdition::class,
        Commands\ImportCensus::class,
        Commands\ResetCensus::class,
        Commands\CreateAdmins::class,
        Commands\ResetAdminsPasswords::class,
        Commands\CacheResults::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (!Schema::hasTable('editions')) return;

        /* Calculate and cache the current edition's results when it closes */
        $edition = Edition::current();

        if ($edition){
            $endDate = strtotime($edition->end_date);
            $minute = ltrim(date('i', $endDate), '0');
            $hour   = date('G', $endDate);
            $day = date('j', $endDate);
            $month = date('n', $endDate);
            $when = "$minute $hour $day $month *";

            $schedule->command('results:cache')->cron($when);
        }
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
