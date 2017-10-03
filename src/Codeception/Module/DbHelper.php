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

use Codeception\Module as CodeceptionModule;

/**
 * Class DbHelper
 * @package Codeception\Module
 */
class DbHelper extends CodeceptionModule
{

    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    public function populateDatabase($I, array $config = [])
    {
        //\Codeception\Util\Debug::debug($config);
        $root = dirname(dirname(dirname(__DIR__)));
        $I->runShellCommand('mysql -h "'.$config['server'].'" -u "'.$config['user'].'" "-p'.$config['password'].'" "'.$config['database'].'" < '.$root.'/'.$config['dump'] . ' 2> /dev/null');
    }

    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    public function runMigration($I)
    {
        $I->runShellCommand('php artisan migrate');
    }

    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     * @param array $config
     * @param string $query
     */
    public function runQuery($I, $config = [], $query = '')
    {
        $queryFile = __DIR__ . '/' . md5($query) . '.sql';
        file_put_contents($queryFile, $query);
        $I->runShellCommand('mysql -h ' . $config['server'] . ' -u ' . $config['user'] . ' -p\'' . $config['password'] . '\' ' . $config['database'] . ' < ' . $queryFile);
        unlink($queryFile);
    }
}
