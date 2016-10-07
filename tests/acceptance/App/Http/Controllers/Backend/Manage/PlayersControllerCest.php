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
        $this->faker = \Faker\Factory::create();
        $this->loginWithAdminUser($I);
        $this->populateDB($I);
        $I->amOnPage('/app/manage/player');

    }

    /**
     * Create the test admin user
     */
    protected function populateDB(AcceptanceTester $I)
    {
        $I->runShellCommand('cd /var/www');
        $I->runShellCommand('php artisan db:seed --class=DatabaseSeeder');
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
        $I->see('Create a new player');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAPlayer(AcceptanceTester $I)
    {
        $params = [];
        $I->wantTo('create a player on the management page');
        $I->amOnPage('/app/manage/game');
        $I->amOnPage('/app/manage/tournament');
        $I->amOnPage('/app/manage/team');
        $I->amOnPage('/app/manage/player');

        $I->see('A phrase I know it doesn\'t exists');

    }

}
