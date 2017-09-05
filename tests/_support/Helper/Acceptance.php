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

    public static function loginToWordpress(\AcceptanceTester $I, $user, $pass, $maxAttempts = 10)
    {

        for($i=0; $i <= $maxAttempts; $i++ ) {
            try {
                $I->amOnPage('/wp/wp-login.php');
                $I->fillField(['id' => 'user_login'], $user);
                $I->fillField(['id' => 'user_pass'], $pass);
                $I->click(['id' => 'wp-submit']);
                $I->waitForText('Dashboard', \BaseAcceptance::TEXT_WAIT_TIMEOUT);
                return;
            } catch (\Exception $e) {
                if ($i === $maxAttempts) {
                    $I->fail("{$i} login attempts were made.");
                }
              continue;
            }
        }
    }
    /**
     * Create a post
     *
     * @param \AcceptanceTester|\FunctionalTester $I
     * @param string $title
     * @param string $content
     */
    public function createAPost($I, $title="", $content="") {

        $I->amOnPage('/wp/wp-admin/post-new.php');
        $I->waitForJs('return jQuery.active == 0', \BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->fillField(['id' => 'title'], $title);
        $I->click(['id' => 'content-html']);
        $I->wait(1);
        $I->fillField(['id' => 'content'], $content);
        $I->wait(5);
        $I->click(['id' => 'publish']);
        $I->waitForText('Post published', \BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->see('Post published');
        $path = $I->executeJS('return document.querySelector("#sample-permalink > a").getAttribute("href")');
        \Codeception\Util\Debug::debug($path);
        $I->amOnPage(parse_url($path, PHP_URL_PATH));
    }
}
