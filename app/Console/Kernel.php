<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        //$schedule->command('app:fetch-telegram-history')->weeklyOn(1, '1:00'); // обновляем историю чатов
        $schedule->command('app:listen-updates')->everyTwoMinutes()->withoutOverlapping(
        ); // запускаем прослушивание входящих сообщений
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
