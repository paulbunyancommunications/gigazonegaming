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
        // Commands\Inspire::class,
        //Commands\Wordpress\Maintenance\Down::class,
        //Commands\Wordpress\Maintenance\Up::class,
        //Commands\Wordpress\SecretKey\Create::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run')->daily()->at('02:00');

        // https://gist.github.com/mauris/11375869#gistcomment-1818901
        $schedule->call(function () {
            $run_command = false;
            $monitor_file_path = storage_path('queue.pid');

            if (file_exists($monitor_file_path)) {
                $pid = file_get_contents($monitor_file_path);
                $result = exec("ps -p $pid --no-heading | awk '{print $1}'");

                if ($result == '') {
                    $run_command = true;
                }
            } else {
                $run_command = true;
            }

            if ($run_command) {
                $command = 'php ' . base_path('artisan') . ' queue:listen --tries=10 > /dev/null & echo $!';
                $number = exec($command);
                file_put_contents($monitor_file_path, $number);
            }
        })
            ->name('monitor_queue_listener')
            ->everyFiveMinutes();
    }
}
