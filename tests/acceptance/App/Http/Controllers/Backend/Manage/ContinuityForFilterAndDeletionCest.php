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
        $I->waitForElementVisible(['id' => 'team_sort'],4);
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
        $I->see('The Tester Player000');
        $I->see('The Tester Player001');
        $I->see('The Tester Player002');
        $I->see('The Tester Player003');
        $I->see('The Tester Player004');
        $I->see('The Tester Player005');

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
        $I->dontSee('The Tester Player000');
        $I->dontSee('The Tester Player001');
        $I->dontSee('The Tester Player002');
        $I->dontSee('The Tester Player003');
        $I->dontSee('The Tester Player004');
        $I->see('The Tester Player005');
    }
    /**
     * @param AcceptanceTester $I
     * Get to the game management page and check for the titles
     */
    public function deleteATeamCheckTournamentAndGameStillThere(AcceptanceTester $I)
    {
        $I->wantTo('delete a team but not the tournament or the game');
        $I->amOnPage('/app/manage/team');
        $I->see('Create a new Team');
        $I->seeElement('input', ['value' => 'Tester Team']);
        $I->executeJS("$('#delete-soft-TesterTeam').closest('form').submit()");
        $I->wait(3);
        $I->see('Create a new Team');
        $I->dontSeeElement('input', ['value' => 'Tester Team']);

        $I->amOnPage('/app/manage/tournament');
        $I->see('Create a new Tournament');
        $I->seeElement('input', ['value' => 'Tester Tournament']);
        $I->executeJS("$('#delete-TesterTournament').closest('form').submit()");
        $I->wait(3);
        $I->see('Create a new Tournament');
        $I->dontSeeElement('input', ['value' => 'Tester Tournament']);

        $I->amOnPage('/app/manage/game');
        $I->see('Create a new Game');
        $I->seeElement('input', ['value' => 'tester-game']);
        $I->executeJS("$('#delete-tester-game').closest('form').submit()");
        $I->wait(3);
        $I->see('Create a new Game');
        $I->dontSeeElement('input', ['value' => 'tester-game']);

    }

    /**
     * @param AcceptanceTester $I
     * Get to the game management page and check for the titles
     */
    public function deleteATournamentCheckTeamIsGoneAndGameStillThere(AcceptanceTester $I)
    {
        $I->wantTo('delete a Tournament cascading to the team but not the game');
        $I->amOnPage('/app/manage/tournament');
        $I->see('Create a new Tournament');
        $I->seeElement('input', ['value' => 'Tester Tournament']);
        $I->executeJS("$('#delete-TesterTournament').closest('form').submit()");
        $I->wait(3);
        $I->see('Create a new Tournament');
        $I->dontSeeElement('input', ['value' => 'Tester Tournament']);

        $I->amOnPage('/app/manage/team');
        $I->see('Create a new Team');
        $I->dontSeeElement('input', ['value' => 'Tester Team']);

        $I->amOnPage('/app/manage/game');
        $I->see('Create a new Game');
        $I->seeElement('input', ['value' => 'tester-game']);
        $I->executeJS("$('#delete-tester-game').closest('form').submit()");
        $I->wait(3);
        $I->see('Create a new Game');
        $I->dontSeeElement('input', ['value' => 'tester-game']);

    }
    /**
     * @param AcceptanceTester $I
     * Get to the game management page and check for the titles
     */
    public function deleteAGameCheckTeamAndTournamentAreGone(AcceptanceTester $I)
    {
        $I->wantTo('delete a game cascading to the team and tournament');
        $I->amOnPage('/app/manage/game');
        $I->see('Create a new Game');
        $I->seeElement('input', ['value' => 'tester-game']);
        $I->executeJS("$('#delete-tester-game').closest('form').submit()");
        $I->wait(3);
        $I->see('Create a new Game');
        $I->dontSeeElement('input', ['value' => 'tester-game']);

        $I->amOnPage('/app/manage/tournament');
        $I->see('Create a new Tournament');
        $I->dontSeeElement('input', ['value' => 'Tester Tournament']);

        $I->amOnPage('/app/manage/team');
        $I->see('Create a new Team');
        $I->dontSeeElement('input', ['value' => 'Tester Team']);
    }

}
