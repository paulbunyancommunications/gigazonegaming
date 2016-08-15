<?php
/**
 * EnsureQueueIsRunning
 *
 * Created 8/15/16 12:21 PM
 * Command for making sure the queue is running
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Console\Commands
 */

namespace App\Console\Commands;


use Illuminate\Console\Command;

class EnsureQueueIsRunning extends Command
{
    protected $signature = 'ensure_queue_is_running';

    protected $description = 'run check to make sure that the queue is still running';

    /**
     * https://gist.github.com/mauris/11375869#gistcomment-1818901
     */
    public function handle()
    {
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
    }
}
