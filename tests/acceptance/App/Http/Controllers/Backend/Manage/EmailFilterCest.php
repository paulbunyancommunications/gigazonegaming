<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use \AcceptanceTester;
use \BaseAcceptance;

/**
 * Class GamesControllerCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class EmailFilterCest extends BaseAcceptance
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
    public function tryToCheckFilterForGameWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/app/manage/email');
        $I->see('Email Filter');
        $I->wantTo('click on a game and see all the email addresses attached');
        $I->selectOption(['id' => 'game_sort'], 'tester-game');
        $I->click("#get_game");
        $I->waitForText("email sender", $this::TEXT_WAIT_TIMEOUT);
        $I->seeElement('#email-getter');
        $I->seeElement('input', ['value' => 'The Tester Player000, The Tester Player001, The Tester Player002, The Tester Player003, The Tester Player004, The Tester Player005, The Tester Player006, The Tester Player007, The Tester Player008, The Tester Player009, The Tester Player010, The Tester Player011, The Tester Player012, The Tester Player013, The Tester Player014, The Tester Player015, The Tester Player016, The Tester Player017, The Tester Player018, The Tester Player019, The Tester Player020, The Tester Player021, The Tester Player022, The Tester Player023, The Tester Player024, The Tester Player025, The Tester Player026, The Tester Player027, The Tester Player028, The Tester Player029, The Tester Player030, The Tester Player031, The Tester Player032, The Tester Player033, The Tester Player034']);
    }
    /**
     * @param AcceptanceTester $I
     * Get to the game management page and check for the titles
     */
    public function tryToCheckFilterForTournamentWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/app/manage/email');
        $I->see('Email Filter');
        $I->wantTo('click on a tournament and see all the email addresses attached');
        $I->selectOption(['id' => 'tournament_sort'], 'Tester Tournament');
        $I->click("#get_tournament");
        $I->waitForText("email sender", $this::TEXT_WAIT_TIMEOUT);
        $I->seeElement('#email-getter');
        $I->seeElement('input', ['value' => 'The Tester Player000, The Tester Player001, The Tester Player002, The Tester Player003, The Tester Player004, The Tester Player005, The Tester Player006, The Tester Player007, The Tester Player008, The Tester Player009, The Tester Player010, The Tester Player011, The Tester Player012, The Tester Player013, The Tester Player014, The Tester Player015, The Tester Player016, The Tester Player017, The Tester Player018, The Tester Player019, The Tester Player020, The Tester Player021, The Tester Player022, The Tester Player023, The Tester Player024, The Tester Player025, The Tester Player026, The Tester Player027, The Tester Player028, The Tester Player029, The Tester Player030, The Tester Player031, The Tester Player032, The Tester Player033, The Tester Player034']);
    }
    /**
     * @param AcceptanceTester $I
     * Get to the game management page and check for the titles
     */
    public function tryToCheckFilterForTeamWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/app/manage/email');
        $I->see('Email Filter');
        $I->wantTo('click on a team and see all the email addresses attached');
        $I->selectOption(['id' => 'team_sort'], 'Tester Team');
        $I->click("#get_team");
        $I->waitForText("email sender", $this::TEXT_WAIT_TIMEOUT);
        $I->seeElement('#email-getter');
        $I->seeElement('input', ['value' => 'The Tester Player000, The Tester Player001, The Tester Player002, The Tester Player003, The Tester Player004']);
    }
    /**
     * @param AcceptanceTester $I
     * Get to the game management page and check for the titles
     */
    public function tryToCheckFilterForPlayerWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/app/manage/email');
        $I->see('Email Filter');
        $I->wantTo('click on a player and see the email addresses attached');
        $I->selectOption(['id' => 'player_sort'],  'Tester Player000');
        $I->click("#get_player");
        $I->waitForText("email sender", $this::TEXT_WAIT_TIMEOUT);
        $I->seeElement('#email-getter');
        $I->seeElement('input', ['value' => 'Tester Player000']);
    }

}
