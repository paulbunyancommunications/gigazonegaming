<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WpUp extends WpDown
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wp:up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bring Wordpress back out of maintenance mode';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
            exec('rm -f '. $path);
            $this->info(PHP_EOL.'Wordpress is back up.'.PHP_EOL);
        } else {
            $this->error(PHP_EOL.'Wordpress is already up'.PHP_EOL);
        }
    }
}
