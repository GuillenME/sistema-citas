<?php

namespace App\Console;

use App\Jobs\AvisarClientesInactivos;
use App\Jobs\EliminarClientesInactivos;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Cancelar citas sin anticipo
        $schedule->command('citas:cancelar-sin-anticipo')
            ->everyMinute();
   
        $schedule->job(new AvisarClientesInactivos)
            ->dailyAt('10:30');

        $schedule->job(new EliminarClientesInactivos)
            ->dailyAt('10:40');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
