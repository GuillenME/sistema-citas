<?php

namespace App\Jobs;

use App\Mail\AvisoEliminacionCuenta;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AvisarClientesInactivos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Usuario::where('role_id', 2)
            ->where('active', 0)
            ->whereDate('updated_at', Carbon::now()->subDays(25))
            ->each(function ($usuario) {
                Mail::to($usuario->email)
                    ->send(new AvisoEliminacionCuenta($usuario));
            });
    }
}
