<?php
namespace Tests\Acceptance\App\Http\Controllers\Api;

use \AcceptanceTester;
use \BaseAcceptance;

/**
 * Class GamesControllerCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class TournamentToGameTeamPlayerControllerCest extends BaseAcceptance
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
    public function checkIfReceiveArrayWhenSendingATournamentId(AcceptanceTester $I)
    {
        $I->wantTo('go to url and see that I received the right information while providing a tournament Id');
        $I->amOnPage('/app/api/tournament_name/Tester%20Tournament');
        $I->see('{ "games": { "name": "tester-game", "title": "The Tester Game" }, "tournaments": { "name": "Tester Tournament", "max_players": 6 }, "teams": { "Tester Team": { "captain": "The Tester Player000", "player_1": "The Tester Player001", "player_2": "The Tester Player002", "player_3": "The Tester Player003", "player_4": "The Tester Player004" } } }');
    }
    /**
     * @param AcceptanceTester $I
     * Get to the game management page and check for the titles
     */
    public function checkIfReceiveArrayWhenSendingATournamentName(AcceptanceTester $I)
    {
        $I->wantTo('go to url and see that I received the right information while providing a tournament Name');
        $I->amOnPage('/app/api/tournament_name/Tester%20Tournament');
        $I->see('{ "games": { "name": "tester-game", "title": "The Tester Game" }, "tournaments": { "name": "Tester Tournament", "max_players": 6 }, "teams": { "Tester Team": { "captain": "The Tester Player000", "player_1": "The Tester Player001", "player_2": "The Tester Player002", "player_3": "The Tester Player003", "player_4": "The Tester Player004" } } }');
    }
    /**
     * @param AcceptanceTester $I
     * Get to the game management page and check for the titles
     */
    public function checkIfReceiveArrayWhenSendingATeamId(AcceptanceTester $I)
    {
        $I->wantTo('go to url and see that I received the right information while providing a team Id');
        $I->amOnPage('/app/api/team_id/1');
        $I->see('{ "games": { "name": "tester-game", "title": "The Tester Game" }, "tournaments": { "name": "Tester Tournament", "max_players": 6 }, "teams": { "Tester Team": { "captain": "The Tester Player000", "player_1": "The Tester Player001", "player_2": "The Tester Player002", "player_3": "The Tester Player003", "player_4": "The Tester Player004" } } }');
    }
    /**
     * @param AcceptanceTester $I
     * Get to the game management page and check for the titles
     */
    public function checkIfReceiveArrayWhenSendingATeamName(AcceptanceTester $I)
    {
        $I->wantTo('go to url and see that I received the right information while providing a team Name');
        $I->amOnPage('/app/api/team_name/Tester%20Team');
        $I->see('{ "games": { "name": "tester-game", "title": "The Tester Game" }, "tournaments": { "name": "Tester Tournament", "max_players": 6 }, "teams": { "Tester Team": { "captain": "The Tester Player000", "player_1": "The Tester Player001", "player_2": "The Tester Player002", "player_3": "The Tester Player003", "player_4": "The Tester Player004" } } }');
    }
}
