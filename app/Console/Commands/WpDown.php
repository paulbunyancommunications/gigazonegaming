<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WpDown extends Command
{
    protected $maintenanceFileName ='.wp-maintenance';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wp:down';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Put Wordpress in maintenance mode';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getFilePath()
    {
        return $this->getPath() .$this->maintenanceFileName;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       if(!file_exists($this->getPath())) {
           mkdir($this->getPath());
           exec('echo '.$this->maintenanceFileName.' > '. $this->getPath() . '.gitignore');
       }

        if(file_exists($this->getFilePath())) {
            $this->error(PHP_EOL.'Wordpress is already down'.PHP_EOL);
        } else {
            exec('touch '.$this->getFilePath());
            $this->info(PHP_EOL . 'Wordpress set to maintenance mode. Either run "artisan wp:up" to '.PHP_EOL.'bring the site back up or manually delete ' .PHP_EOL . $this->getFilePath() . '.' . PHP_EOL);
        }
    }

    /**
     * @return string
     */
    protected function getPath()
    {
        return storage_path() . '/wordpress/';
    }
}
