<?php

namespace App\Console;

use App\Models\RevokedBrowserSession;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('backup:dbbackup')->daily();
        $schedule->command('backup:cleanup --days=7')->weekly();
        $schedule->command('model:prune', ['--model' => [RevokedBrowserSession::class]])->monthlyOn(1, '12:00');
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
