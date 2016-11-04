<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{

    public static function loadJQuery(\AcceptanceTester $I, $callback)
    {
        $I->executeJS(
            "!window.jQuery && document.write('<script type=\"text/javascript\" src=\"/bower_components/jquery/dist/jquery.min.js\"></script>');
            $(function() {" . $callback . "});"
        );
    }

    public function _beforeSuite($settings = [])
    {
        exec('php artisan migrate:refresh');
    }

    public static function loginToWordpress(\AcceptanceTester $I, $user, $pass)
    {
        try {
            $I->amOnPage('/wp/wp-login.php');
            $I->fillField(['id' => 'user_login'], $user);
            $I->fillField(['id' => 'user_pass'], $pass);
            $I->click(['id' => 'wp-submit']);
            $I->waitForText('Dashboard', 30);
        } catch (\Exception $e) {
            $I->comment('First attempt failed to login, try a second time');
            $I->amOnPage('/wp/wp-login.php');
            $I->fillField(['name' => 'log'], $user);
            $I->fillField(['name' => 'pwd'], $pass);
            $I->click(['name' => 'wp-submit']);
            $I->waitForText('Dashboard', 30);
        }
    }
}
