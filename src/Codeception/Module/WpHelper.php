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


/**
 * Class WpHelper
 * @package Codeception\Module
 */
class WpHelper extends CodeceptionModule
{

    /**
     * Log a user into the Wordpress backend
     *
     * @param \AcceptanceTester|\FunctionalTester $I
     * @param $user
     * @param $password
     */
    public function logIntoWpAdmin($I, $user, $password)
    {
        $I->amOnPage('/wp/wp-login.php');
        $I->fillField(['id' => 'user_login'], $user);
        $I->fillField(['id' => 'user_pass'], $password);
        $I->click(['id' => 'wp-submit']);
        $I->waitForText('Dashboard');

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
