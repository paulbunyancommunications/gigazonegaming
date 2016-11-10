<?php
namespace Tests\Acceptance\App\Http\Controllers\Api;

use \AcceptanceTester;
use \BaseAcceptance;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
     */
    public function checkIfReceiveArrayWhenSendingATournamentId(AcceptanceTester $I)
    {
        $I->wantTo('go to url and see that I received the right information while providing a tournament Id');
        $I->amOnPage('/app/api/tournament_id/16');
        $I->see("games");
        $I->see("tournaments");
        $I->see("teams");
        $I->see("tester-game");
        $I->see("Tester Tournament");
        $I->see("Tester Team");
        $I->see("The Tester Player000");
    }
    /**
     * @param AcceptanceTester $I
     */
    public function checkIfReceiveFalseWhenSendingAWrongTournamentId(AcceptanceTester $I)
    {
        $I->wantTo('go to url and see that I received false while providing a wrong tournament id');
        $I->amOnPage('/app/api/tournament_id/99999');
        $I->see('false');
    }
    /**
     * @param AcceptanceTester $I
     */
    public function checkIfReceiveArrayWhenSendingATournamentName(AcceptanceTester $I)
    {
        $I->wantTo('go to url and see that I received the right information while providing a tournament Name');
        $I->amOnPage('/app/api/tournament_name/Tester%20Tournament');
        $I->see("games");
        $I->see("tournaments");
        $I->see("teams");
        $I->see("tester-game");
        $I->see("Tester Tournament");
        $I->see("Tester Team");
        $I->see("The Tester Player000");
    }
    /**
     * @param AcceptanceTester $I
     */
    public function checkIfReceiveFalseWhenSendingAWrongTournamentName(AcceptanceTester $I)
    {
        $I->wantTo('go to url and see that I received false while providing a wrong tournament Name');
        $I->amOnPage('/app/api/tournament_name/TesterTournamentNotExistant');
        $I->see('false');
    }
    /**
     * @param AcceptanceTester $I
     */
    public function checkIfReceiveArrayWhenSendingATeamId(AcceptanceTester $I)
    {
        $I->wantTo('go to url and see that I received the right information while providing a team Id');
        $I->amOnPage('/app/api/team_id/1');
        $I->see("games");
        $I->see("tournaments");
        $I->see("teams");
        $I->see("tester-game");
        $I->see("Tester Tournament");
        $I->see("Tester Team");
        $I->see("The Tester Player000");
    }
    /**
     * @param AcceptanceTester $I
     */
    public function checkIfReceiveFalseWhenSendingAWrongTeamId(AcceptanceTester $I)
    {
        $I->wantTo('go to url and see that I received false while providing a wrong team Id');
        $I->amOnPage('/app/api/team_id/9999');
        $I->see('false');
    }
    /**
     * @param AcceptanceTester $I
     */
    public function checkIfReceiveArrayWhenSendingATeamName(AcceptanceTester $I)
    {
        $I->wantTo('go to url and see that I received the right information while providing a team Name');
        $I->amOnPage('/app/api/team_name/Tester%20Team');
        $I->see("games");
        $I->see("tournaments");
        $I->see("teams");
        $I->see("tester-game");
        $I->see("Tester Tournament");
        $I->see("Tester Team");
        $I->see("The Tester Player000");
    }
    /**
     * @param AcceptanceTester $I
     */
    public function checkIfReceiveFalseWhenSendingAWrongTeamName(AcceptanceTester $I)
    {
        $I->wantTo('go to url and see that I received false while providing a wrong team Name');
        $I->amOnPage('/app/api/team_name/TesterTeamNotExistant');
        $I->see('false');
    }
}
