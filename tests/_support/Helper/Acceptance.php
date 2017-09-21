<?php

namespace Helper;

use Pbc\Bandolier\Type\Arrays;
use utilphp\util;

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

        for ($i = 0; $i <= $maxAttempts; $i++) {
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
     * Provide a string for title and content, or pass an array for the second param to change extra fields
     * the meta field should be set like:
     * ['webdriver_command','selector','value'], Examples:
     * * ['fillField','#someId','abc123']
     * * ['selectOption','#someSelectField','ABC 123']
     *
     * @param \AcceptanceTester|\FunctionalTester $I
     * @param string|array $title
     * @param string $content
     */
    public function createAPost($I, $title = "", $content = "")
    {

        $faker = \Faker\Factory::create();
        if (is_array($title)) {
            extract(Arrays::defaultAttributes([
                    'title' => $faker->sentence(),
                    'content' => $faker->paragraph(),
                    'meta' => [],
                    'customFields' => []
                ]
                , $title)
            );
        }

        $I->amOnPage('/wp/wp-admin/post-new.php');
        // show the settings dialog link
        $I->click('#show-settings-link');

        $I->fillField(['id' => 'title'], $title);
        $exist = util::str_to_bool($I->executeJS("return !!document.getElementById('content-html')"));
        if ($exist) {
            $I->click(['id' => 'content-html']);
            $I->wait(5);
        }
        $I->click(['id' => 'content']);
        $I->fillField(['id' => 'content'], $content);

        // run though the meta field and set any extra fields that is contains
        if (isset($meta) && count($meta) > 0) {
            for($i=0, $iCount=count($meta); $i < $iCount; $i++) {
                $I->{$meta[$i][0]}($meta[$i][1],$meta[$i][2]);
            }
        }
        // run though the custom fields. since there's no good way to know what
        // the name/id of the input is they will be looked up via the value
        if (isset($customFields) && count($customFields) > 0) {
            $I->scrollTo('#postcustom');
            for($i=0, $iCount=count($customFields); $i < $iCount; $i++) {
                $I->fillField('#' . str_replace('key','value',$I->executeJS('return document.querySelectorAll(\'input[value="'. $customFields[$i][0] .'"]\')[0].id;')), $customFields[$i][1]);
            }
        }


        $I->wait(5);
        $I->click(['id' => 'publish']);
        $I->waitForText('Post published', \BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->see('Post published');
        $path = $I->executeJS('return document.querySelector("#sample-permalink > a").getAttribute("href")');
//        \Codeception\Util\Debug::debug($path);
        $I->amOnPage(parse_url($path, PHP_URL_PATH));
    }

    /**
     * @param \AcceptanceTester|\FunctionalTester $I
     * @param $timeOut
     * @return mixed
     */
    public function checkIfJQueryIsWorking($I, $timeOut = "notSet")
    {
        if ($timeOut == "notSet") {
            $timeOut = \BaseAcceptance::TEXT_WAIT_TIMEOUT;
        }
        $I->waitForJs('return jQuery.active == 0', $timeOut);
        $exist = $I->executeJS("return !!jQuery('body').length;"); //this will activate jquery if it wasn't still
        $exist = $I->executeJS("return !!jQuery('body').length;"); //this will let you know that yes it is there!
        return true;
    }
}
