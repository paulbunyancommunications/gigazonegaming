<?php
/**
 * WpHelper
 *
 * Created 6/10/16 3:28 PM
 * Helper actions for wordpress
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package Codeception\Module
 */

namespace Codeception\Module;

use Codeception\Module as CodeceptionModule;
use PHPUnit_Framework_AssertionFailedError;


/**
 * Class WpHelper
 * @package Codeception\Module
 */
class WpHelper extends CodeceptionModule
{

    const TEXT_WAIT_TIMEOUT = 30;

    /**
     * Log a user into the Wordpress backend
     *
     * @param \AcceptanceTester|\FunctionalTester $I
     * @param $user
     * @param $pass
     * @param $maxAttempts
     */
    public function logIntoWpAdmin($I, $user, $pass, $maxAttempts)
    {
        for($i=0; $i <= $maxAttempts; $i++ ) {
            try {
                $I->amOnPage('/wp/wp-login.php');
                $I->fillField(['id' => 'user_login'], $user);
                $I->fillField(['id' => 'user_pass'], $pass);
                $I->click(['id' => 'wp-submit']);
                $I->waitForText('Dashboard', self::TEXT_WAIT_TIMEOUT);
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
     * Log a user out of the wordpress backend
     *
     * @param \AcceptanceTester|\FunctionalTester $I
     */
    public function logOutOfWpAdmin($I)
    {
        $I->amOnPage('/wp/wp-login.php?loggedout=true');
        $I->waitForText('You are now logged out.');
    }
}
