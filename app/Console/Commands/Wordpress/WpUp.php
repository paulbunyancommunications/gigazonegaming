<?php

namespace App\Console\Commands\Wordpress;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class WpUp
 *
 * Pull Wordpress out of maintenance mode
 * Maintenance mode is handled by Wordpress theme with a
 * int hook to check for the maintenance file
 * @package App\Console\Commands
 */
class WpUp extends WpDown
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wp:up {--dir=wordpress} {--file=.wp-maintenance}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bring Wordpress back out of maintenance mode';
    protected $destroyer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->destroyer = new WpDestroy($this);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->getFilePath();
        if(file_exists($this->getFilePath())) {
            $this->destroyer->removeMaintenanceFile($path);
            $this->info(PHP_EOL.'Wordpress is back up.'.PHP_EOL);
        } else {
            $this->error(PHP_EOL.'Wordpress is already up'.PHP_EOL);
        }
    }
}
