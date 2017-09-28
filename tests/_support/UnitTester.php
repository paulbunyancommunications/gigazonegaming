<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class UnitTester extends \Codeception\Actor
{
    use _generated\UnitTesterActions;

   /**
    * Define custom actions here
    */

    public function ReloadDBAndMigrate()
    {
        $this->ReloadDB();
        $this->MigrateDB();
    }
    public function ReloadDB()
    {
        $dir = dirname(dirname(dirname(__DIR__))) . '/database/dump/gigazone_wp.sql';
        exec('mysql -h "'.env('DB_HOST').'" -u "'.env('DB_USERNAME').'" "-p'.env('DB_PASSWORD').'" "'.env('DB_DATABASE').'" < '.$dir. ' 2> /dev/null');
    }
    public function MigrateDB()
    {
        exec('php artisan migrate');
    }
    public function ClearCache()
    {
        exec('php artisan cache:clear');
    }
}
