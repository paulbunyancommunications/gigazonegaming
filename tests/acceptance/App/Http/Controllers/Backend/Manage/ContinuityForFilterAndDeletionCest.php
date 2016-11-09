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
        $I->waitForJS("return $('.filter-tester-game').click();", 60);
//        $I->click(".filter-tester-game");
        $I->waitForText('Create a new Tournament', BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->seeOptionIsSelected('#game_sort', 'tester-game');
        $I->wantTo('click on a tournament and land on team page with an applied filter');
        $I->click(".filter-TesterTournament");
        $I->waitForJS("return $('.filter-TesterTournament').click();", 60);
//        $I->see('Create a new Team');
        $I->seeOptionIsSelected('#tournament_sort', 'Tester Tournament');
        $I->wantTo('click on a team and land on player page with an applied filter');
        $I->waitForJS("return $('.filter-TesterTeam').click();", 60);
//        $I->click(".filter-TesterTeam");
        $I->waitForText('Create a new Player', BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->waitForText('The Tester Player000', BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->waitForText('The Tester Player001', BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->waitForText('The Tester Player002', BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->waitForText('The Tester Player003', BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->waitForText('The Tester Player004', BaseAcceptance::TEXT_WAIT_TIMEOUT);
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
        $I->waitForJS('return $("form").attr("onsubmit", "nothing = 1");', 60);
//        $I->click(".delete_soft-form-TesterTeam");
        $I->waitForJS("return $('.delete_soft-form-TesterTeam').click();", 60);
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
        $I->waitForJS('return $("form").attr("onsubmit", "nothing = 1");', 60);
        $I->waitForJS("return $('.delete_hard-form-TesterTeam').click();", 60);
        $I->waitForText('Create a new Team', BaseAcceptance::TEXT_WAIT_TIMEOUT);
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
        $I->see( 'Tester Team');
        $I->waitForJS('return $("form").attr("onsubmit", "nothing = 1");', 60);
        $I->waitForJS("return $('.delete_soft-form-TesterTeam').click();", 60);
        $I->waitForText('Create a new Team', BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->dontSeeLink('Tester Team');

        $I->amOnPage('/app/manage/tournament');
        $I->see('Create a new Tournament');
        $I->see( 'Tester Tournament');
        $I->waitForJS('return $("form").attr("onsubmit", "nothing = 1");', 60);
        $I->waitForJS("return $('.delete-TesterTournament').click();", 60);
        $I->waitForText('Create a new Tournament', BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->dontSeeLink('Tester Tournament');

        $I->amOnPage('/app/manage/game');
        $I->see('Create a new Game');
        $I->see( 'tester-game');
        $I->waitForJS('return $("form").attr("onsubmit", "nothing = 1");', 60);
        $I->waitForJS("return $('.delete-tester-game').click();", 60);
        $I->waitForText('Create a new Game', BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->dontSeeLink('tester-game');

    }

    /**
     * @param AcceptanceTester $I
     * Get to the game management page and check for the titles
     */
    public function deleteATournamentCheckTeamIsGoneAndGameStillThere(AcceptanceTester $I)
    {
        $I->wantTo('delete a Tournament cascading to the team but not the game');
        $I->amOnPage('/app/manage/tournament');
        $I->see('Create a new Tournament', ['id' => 'gaming-page-title']);
        $I->see('Tester Tournament');
        $I->waitForJS('return $("form").attr("onsubmit", "nothing = 1");', 60);
        $I->waitForJS("return $('.delete-TesterTournament').click();", 60);
        $I->waitForText('Create a new Tournament', BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->dontSeeElement('input', ['value' => 'Tester Tournament']);

        $I->amOnPage('/app/manage/team');
        $I->see('Create a new Team');
        $I->dontSeeElement('input', ['value' => 'Tester Team']);

        $I->amOnPage('/app/manage/game');
        $I->see('Create a new Game');
        $I->see( 'tester-game');
        $I->waitForJS('return $("form").attr("onsubmit", "nothing = 1");', 60);
        $I->waitForJS("return $('.delete-tester-game').click();", 60);
        $I->waitForText('Create a new Game', BaseAcceptance::TEXT_WAIT_TIMEOUT);
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
        $I->see( 'tester-game');
        $I->waitForJS('return $("form").attr("onsubmit", "nothing = 1");', 60);
        $I->waitForJS("return $('.delete-tester-game').click();", 60);
        $I->waitForText('Create a new Game', BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->dontSeeElement('input', ['value' => 'tester-game']);

        $I->amOnPage('/app/manage/tournament');
        $I->see('Create a new Tournament');
        $I->dontSeeElement('input', ['value' => 'Tester Tournament']);

        $I->amOnPage('/app/manage/team');
        $I->see('Create a new Team');
        $I->dontSeeElement('input', ['value' => 'Tester Team']);


    }

}
