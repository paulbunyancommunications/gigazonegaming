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
        // https://gist.github.com/mauris/11375869#gistcomment-1769111
        $schedule->call(
            /** @codeCoverageIgnoreStart */
            function () {
                // you can pass queue name instead of default
                \Artisan::call('queue:listen', array('--queue' => 'default'));
            }
            /** @codeCoverageIgnoreEnd */
        )->name('ensurequeueisrunning')->withoutOverlapping()->everyMinute();
    }
}
