<?php
/**
 * WpGenerate
 *
 * Created 4/18/16 1:03 PM
 * Description of this file here....
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Console\Commands\Wordpress
 * @subpackage Subpackage
 */

namespace App\Console\Commands\Wordpress;


/**
 * Class WpGenerate
 * @package App\Console\Commands\Wordpress
 */
class WpGenerate
{

    public function __construct(WpDown $wpDown)
    {
        $this->wpDown = $wpDown;
    }

    /**
     * Create .gitignore file
     *
     * @return mixed
     */
    public function makeGitIgnore()
    {
        $file = $this->wpDown->option('file');
        $path = $this->wpDown->getPath();
        if(!file_exists($path)) {
            mkdir($path);
        }
        return exec("echo {$file} > {$path}.gitignore");
    }

    /**
     * Create maintenance mode file
     *
     * @return mixed
     */
    public function makeMaintenanceFile()
    {   $filePath = $this->wpDown->getFilePath();
        return exec("touch {$filePath}");
    }
    
}