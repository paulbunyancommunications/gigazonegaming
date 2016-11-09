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
        //todo I changed this to check if it will run this time but we can remove the line if test doesnt pass and comment out the old one.
        exec('php artisan wp:up');
//        $I->runShellCommand('php artisan wp:up');
        exec("rm -f public_html/.gitignore || true");
    }

    // tests
    public function tryToSetWordpressToMaintenanceMode(AcceptanceTester $I)
    {
        //todo I changed this to check if it will run this time but we can remove the line if test doesnt pass and comment out the old one.
        exec('php artisan wp:down');
//        $I->runShellCommand('php artisan wp:down');
        $I->amOnPage('/');
        $I->see('Briefly unavailable for scheduled maintenance. Check back in a minute.');
    }
    
    public function checkThatTheMaintenanceFileWasCreated(AcceptanceTester $I)
    {
        //todo I changed this to check if it will run this time but we can remove the line if test doesnt pass and comment out the old one.
        exec('php artisan wp:down');
//        $I->runShellCommand('php artisan wp:down');
        $I->seeFileFound('public_html/wp/.maintenance');
        $I->seeInShellOutput('Wordpress set to maintenance mode.');
    }
    public function checkThatTheMaintenanceFileWasDestroyed(AcceptanceTester $I)
    {
        $file = '.some_file_name';
        $dir='some_other_dir';
        //todo I changed this to check if it will run this time but we can remove the line if test doesnt pass and comment out the old one.
        exec('php artisan wp:down --dir='.$dir.' --file='.$file);
//        $I->runShellCommand('php artisan wp:down --dir='.$dir.' --file='.$file);
        exec('php artisan wp:up --dir='.$dir.' --file='.$file);
//        $I->runShellCommand('php artisan wp:up --dir='.$dir.' --file='.$file);
        $I->dontSeeFileFound('storage/wordpress/.wp-maintenance');
        exec('php artisan wp:up --dir='.$dir.' --file='.$file);
//        $I->runShellCommand('php artisan wp:up --dir='.$dir.' --file='.$file);
        // cleanup
        exec("rm -rf public_html/".$dir.' || true');

    }
}