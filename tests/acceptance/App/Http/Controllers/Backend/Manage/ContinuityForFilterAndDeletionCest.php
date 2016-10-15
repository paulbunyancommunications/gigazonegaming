<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use \AcceptanceTester;
use \BaseAcceptance;

/**
 * Class GamesControllerCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class ContinuityForFilterAndDeletionCest extends BaseAcceptance
{

    /**
     * @param AcceptanceTester $I
     */
    public $faker;
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->populateDB($I);
        $this->loginWithAdminUser($I);

    }

    /**
     * Create the test admin user
     */
    protected function populateDB(AcceptanceTester $I)
    {
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
     * Get to the game management page and check for the titles
     */
    public function tryToCheckFilterContinuity(AcceptanceTester $I)
    {
        $I->wantTo('click on a game and land on tournament page with an applied filter');
        $I->amOnPage('/app/manage/game');
        $I->see('Create a new game');
        $I->click('tester-game');
        $I->see('Create a new Tournament');
        $I->seeOptionIsSelected('#game_sort', 'tester-game');
        $I->wantTo('click on a tournament and land on team page with an applied filter');
        $I->click('Tester Tournament');
        $I->see('Create a new Team');
        $I->seeOptionIsSelected('#tournament_sort', 'Tester Tournament');
        $I->wantTo('click on a team and land on player page with an applied filter');
        $I->click('Tester Team');
        $I->see('Create a new Player');
        $I->executeJS("$('#team_sort').show();");
        $I->waitForElementVisible(['id' => 'team_sort'], 30);
        $I->seeOptionIsSelected('#team_sort', 'Tester Team');
    }
    /**
     * @param AcceptanceTester $I
     * Get to the game management page and check for the titles
     */
    public function tryToDeleteATeamButNotThePlayers(AcceptanceTester $I)
    {
        $I->wantTo('delete a team but not the players');
        $I->amOnPage('/app/manage/team');
        $I->see('Create a new Team');
        $I->executeJS("$('#delete-soft-TesterTeam').closest('form').submit()");
        $I->wait(3);
        $I->dontSee('Tester Team');
        $I->amOnPage('/app/manage/player');
        $I->see('Create a new Player');
        $I->see('The Tester Player0');

    }
    /**
     * @param AcceptanceTester $I
     * Get to the game management page and check for the titles
     */
    public function tryToDeleteATeamAndThePlayers(AcceptanceTester $I)
    {
        $I->wantTo('delete a team and the players');
        $I->amOnPage('/app/manage/team');
        $I->see('Create a new Team');
        $I->executeJS("$('#delete-hard-TesterTeam').closest('form').submit()");
        $I->wait(3);
        $I->see('Create a new Team');
        $I->dontSee('Tester Team');
        $I->amOnPage('/app/manage/player');
        $I->see('Create a new Player');
        $I->dontSee('The Tester Player0');
    }

}
