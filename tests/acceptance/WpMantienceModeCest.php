<?php
namespace Tests\Acceptance;
use \AcceptanceTester;

class WpMantienceModeCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
        $I->runShellCommand('php artisan wp:up');
    }

    // tests
    public function tryToSetWordpressToMaintenanceMode(AcceptanceTester $I)
    {
        $I->runShellCommand('php artisan wp:down');
        $I->amOnPage('/');
        $I->see('The site down for maintenance.');
    }
    
    public function checkThatTheMaintenanceFileWasCreated(AcceptanceTester $I)
    {
        $I->runShellCommand('php artisan wp:down');
        $I->seeFileFound('storage/wordpress/.wp-maintenance');
        $I->seeInShellOutput('Wordpress set to maintenance mode.');


    }
    public function checkThatTheMaintenanceFileWasDestroyed(AcceptanceTester $I)
    {
        $file = '.some_file_name';
        $dir='some_other_dir';
        $I->runShellCommand('php artisan wp:down --dir='.$dir.' --file='.$file);
        $I->runShellCommand('php artisan wp:up --dir='.$dir.' --file='.$file);
        $I->dontSeeFileFound('storage/wordpress/.wp-maintenance');
        $I->runShellCommand('php artisan wp:up --dir='.$dir.' --file='.$file);

    }
}