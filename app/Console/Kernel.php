<?php

namespace App\Console;

use App\PlayerPriceWatch;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;

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
        $schedule->call(function () {
            $player = PlayerPriceWatch::first();

            $response = Http::get("https://www.futbin.com/20/playerPrices?player={$player->futbin_id}");

            $price = $response->json()[$player->futbin_id]['prices']['ps']['LCPrice'];
            $price = (int) str_replace(',', '', $price);

            if ($player->min_amount && $player->min_amount >= $price) {
                dd('notify min');
            }

            if ($player->max_amount && $price >= $player->max_amount) {
                dd('notify max');
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
