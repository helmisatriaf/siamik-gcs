<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

     protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->command('assessment:deactivate')
        // ->dailyAt('00:01'); // Jalankan setiap hari jam 00:01
        ->everyMinute();
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