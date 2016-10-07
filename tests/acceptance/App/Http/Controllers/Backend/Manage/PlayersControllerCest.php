<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use \AcceptanceTester;
use \BaseAcceptance;

/**
 * Class PlayersControllerCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class PlayersControllerCest extends BaseAcceptance{
    /**
     * @param AcceptanceTester $I
     */
    public $faker;
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->populateDB($I);
        $this->loginWithAdminUser($I);
        $I->amOnPage('/app/manage/player');

    }

    /**
     * Create the test admin user
     */
    protected function populateDB(AcceptanceTester $I)
    {
//        $I->runShellCommand('cd /var/www');
        exec('php artisan db:seed --class=DatabaseSeeder');
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
        $this->logoutOfWp($I);
        parent::_after($I);
    }


    /**
     * @param AcceptanceTester $I
     * Get to the player management page and check for the max_playerss
     */
    public function tryToGetToThePlayerApp(AcceptanceTester $I)
    {
        $I->wantTo('get to the player management page');
        $I->amOnPage('/app/manage/player');
        $I->see('Create a new Player');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAPlayer(AcceptanceTester $I)
    {
        $name = "DummyUser Tester";
        $username = "DummyUser-Tester";
        $email = "DummyUser@Tester.com";
        $phone = "(218) - 444 - 4444";
        $I->wantTo('create a player on the management page');
        $I->amOnPage('/app/manage/player');
        $I->fillField(['id' => 'name'],  $name);
        $I->fillField(['id' => 'username'],  $username);
        $I->fillField(['id' => 'email'],  $email);
        $I->fillField(['id' => 'phone'],  $phone);
        $I->click("Save");

        $I->see('Update Player: “'.$name.'”');
        $I->dontSee("Tester Game");
        $I->dontSee("Tester Tournament");
        $I->dontSee("Tester Team");

        $I->selectOption('Which OS do you use?', array('text' => 'Windows'));



        //$name = $I->grabValueFrom('Name');
        //$name = $I->grabValueFrom('input[name=username]');
        //$name = $I->grabValueFrom('descendant-or-self::form/descendant::input[ * `name`  = 'username']');
        //$name = $I->grabValueFrom(['name' => 'username']);


        $I->see('A phrase I know it doesn\'t exists');

    }

}
