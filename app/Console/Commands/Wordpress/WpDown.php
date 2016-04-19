<?php

namespace App\Console\Commands\Wordpress;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;


/**
 * Class WpDown
 *
 * Put wordpress in a maintenance mode
 * Maintenance mode is handled by Wordpress theme with a
 * int hook to check for the maintenance file
 * @package App\Console\Commands
 */
class WpDown extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wp:down {--dir=wordpress} {--file=.wp-maintenance}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Put Wordpress in maintenance mode';
    protected $generator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->generator = new WpGenerate($this);
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->getPath() . $this->option('file');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = $this->option('file');
        $path = $this->getPath();
        $filePath = $this->getFilePath();
        $eol = PHP_EOL;
        
        $this->generator->makeGitIgnore($file, $path);
        
        if (file_exists($this->getFilePath())) {
            $this->error(PHP_EOL . 'Wordpress is already down' . PHP_EOL);
        } else {
            $this->generator->makeMaintenanceFile($filePath);
            $this->info("{$eol}Wordpress set to maintenance mode. Either run \"artisan wp:up\" to {$eol} bring the site back up or manually delete {$eol} {$filePath}.");
        }
    }

    /**
     * @return string
     */
    public function getPath()
    {
        $storage = storage_path();
        $dir = $this->option('dir');
        return "{$storage}/{$dir}/";
    }
}
