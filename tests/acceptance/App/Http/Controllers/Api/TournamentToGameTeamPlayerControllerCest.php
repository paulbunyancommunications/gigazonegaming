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
    public function checkIfReceiveArrayWhenSendingAId(AcceptanceTester $I)
    {
        $I->wantTo('click on a game and land on tournament page with an applied filter');
        $I->amOnPage('/app/api/tournament_name/Tester%20Tournament');
        $I->see('{ "game": { "name": "tester-game", "title": "The Tester Game" }, "tournament": { "name": "Tester Tournament", "max_players": 6 }, "teams": { "Tester Team": { "captain": "The Tester Player000", "player_1": "The Tester Player001", "player_2": "The Tester Player002", "player_3": "The Tester Player003", "player_4": "The Tester Player004" } } }');
    }
    /**
     * @param AcceptanceTester $I
     * Get to the game management page and check for the titles
     */
    public function checkIfReceiveArrayWhenSendingAName(AcceptanceTester $I)
    {
        $I->wantTo('click on a game and land on tournament page with an applied filter');
        $I->amOnPage('/app/api/tournament_name/Tester%20Tournament');
        $I->see('{ "game": { "name": "tester-game", "title": "The Tester Game" }, "tournament": { "name": "Tester Tournament", "max_players": 6 }, "teams": { "Tester Team": { "captain": "The Tester Player000", "player_1": "The Tester Player001", "player_2": "The Tester Player002", "player_3": "The Tester Player003", "player_4": "The Tester Player004" } } }');
    }
}
