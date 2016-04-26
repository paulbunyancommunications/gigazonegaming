<?php

namespace App\Console\Commands\Wordpress\SecretKey;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class Create
 *
 * Create wordpress app security keys
 * @package App\Console\Commands
 */
class Create extends Command
{
    /**
     * @var string
     */
    protected $signature = 'wp:keys {--file=.env}';
    /**
     * @var string
     */
    protected $description = 'Generate Wordpress Authentication keys';
    /**
     * @var string
     */
    protected $findRegEx = '/(#KEY#)(=)(").*?(")/is';
    /**
     * @var string
     */
    protected $findRegExPlaceholder = '#KEY#';

    /**
     * @var array
     */
    protected $keys = [
        'AUTH_KEY',
        'SECURE_AUTH_KEY',
        'LOGGED_IN_KEY',
        'NONCE_KEY',
        'AUTH_SALT',
        'SECURE_AUTH_SALT',
        'LOGGED_IN_SALT',
        'NONCE_SALT'
    ];

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
     * Handle generating the Wordpress auth keys
     */
    public function handle()
    {
        /** @var string $path path to the env file */
        $path = $this->getFilePath();
        if(!file_exists($path)) {
            file_put_contents($path, "");
        }
        
        /** @var string $content current content in the env file */
        $content = file_get_contents($path);

        foreach($this->generateKeys() as $key) {
            /** try and match the value in the env file by regex, if not 
             * found then append the key to the bottom of the 
             * file 
             */
            preg_match($key['regex'], $content, $matches);
            if($matches) {
                $content = preg_replace($key['regex'], $key['value'], $content);
            } else {
                $content .= $key['value'].PHP_EOL;
            }
        }
        /** apply changes */
        file_put_contents($path, $content);
        $this->info('Wordpress keys regenerated');
    }

    /**
     * build file path to env file
     *
     * @return string
     */
    private function getFilePath()
    {
        return base_path() . '/'. $this->option('file');
    }

    /**
     * Generate regex used for replacing the key value in the env file and
     * the value that should be used in place
     * @return array
     */
    private function generateKeys()
    {
        $content = [];
        for($i=0; $i < count($this->keys); $i++) {
            $holder = [];

            $holder['regex'] = str_replace_first($this->findRegExPlaceholder, $this->keys[$i], $this->findRegEx);
            $holder['value'] = $this->keys[$i].'="'. str_random(64). '"';
            array_push($content, $holder);
        }
        
        return $content;
    }
}