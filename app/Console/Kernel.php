<?php

namespace App\Console;

use App\Schedulers\FixtureStateCheckerScheduler;
use App\Schedulers\RunningFixtureTrackerScheduler;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(new FixtureStateCheckerScheduler)->name('fixture_status_check')->everyMinute();
//        $schedule->call(new RunningFixtureTrackerScheduler)->name('fixture_progress')->withoutOverlapping()->everyMinute();
        $schedule->call(new RunningFixtureTrackerScheduler)->name('fixture_progress')->everyMinute();
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
