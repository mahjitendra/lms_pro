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
        \App\Console\Commands\AI\TrainModelCommand::class,
        \App\Console\Commands\AI\ProcessVideoCommand::class,
        \App\Console\Commands\AI\GenerateRecommendationsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Example of scheduling a command:
        // $schedule->command('ai:generate-recommendations --all')->daily();
        //
        // This would run the recommendation generation for all users every day.
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