<?php
/**
 * DbHelper
 *
 * Created 11/16/15 10:27 AM
 * Help with the population of database with tests
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Codeception\Module
 */

namespace Codeception\Module;

use Codeception\Module;

/**
 * Class DbHelper
 * @package Codeception\Module
 */
class DbHelper extends Module
{

    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    public function populateDatabase($I, array $config = [])
    {
        //\Codeception\Util\Debug::debug($config);
        $root = dirname(dirname(dirname(__DIR__)));
        $I->runShellCommand('mysql -h "'.$config['server'].'" -u "'.$config['user'].'" "-p'.$config['password'].'" "'.$config['database'].'" < '.$root.'/'.$config['dump'] . ' 2> nul');
    }

    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    public function runMigration($I)
    {
        $I->runShellCommand('php artisan migrate:refresh');
    }

}