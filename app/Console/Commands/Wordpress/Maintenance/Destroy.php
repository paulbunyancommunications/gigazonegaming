<?php
/**
 * WpDestroy
 *
 * Created 4/18/16 1:08 PM
 * Destruction of maintenance state
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Console\Commands\Wordpress
 */

namespace App\Console\Commands\Wordpress\Maintenance;


/**
 * Class WpDestroy
 * @package App\Console\Commands\Wordpress
 */
class Destroy
{
    protected $wpUp;

    public function __construct(Up $wpup)
    {
        $this->wpUp = $wpup;
    }

    /**
     * Remove the maintenance file
     * @return mixed
     */
    public function removeMaintenanceFile()
    {
        $path = $this->wpUp->getFilePath();
        return exec("rm -f {$path}");
    }
    
}