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
class PrintingControllerCest extends BaseAcceptance
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
    public function checkIfGameWorksCorrectly(AcceptanceTester $I)
    {
        $I->wantTo('go to the game controller and try to print one of the elements and see if the response is correct');
        $I->amOnPage('/app/manage/game');
        $I->see('Create a new Game');
        $I->click(".print-tester-game");
        $I->waitForText('Print', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Download all');
        $I->see('The Tester Player000');
        $I->see('Tester Player000');
        $I->see('(218)-444-0028');
        $I->see('Tester Player034');
    }
    /**
     * @param AcceptanceTester $I
     */
    public function checkIfTournamentWorksCorrectly(AcceptanceTester $I)
    {
        $I->wantTo('go to the tournament controller and try to print one of the elements and see if the response is correct');
        $I->amOnPage('/app/manage/tournament');
        $I->see('Create a new Tournament');
        $I->click(".print-TesterTournament");
        $I->waitForText('Print', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Download all');
        $I->see('The Tester Player000');
        $I->see('Tester Player000');
        $I->see('(218)-444-028');
        $I->see('Tester Player034');
    }
    /**
     * @param AcceptanceTester $I
     */
    public function checkIfTeamWorksCorrectly(AcceptanceTester $I)
    {
        $I->wantTo('go to the team controller and try to print one of the elements and see if the response is correct');
        $I->amOnPage('/app/manage/team');
        $I->see('Create a new Team');
        $I->click(".print-TesterTeam");
        $I->waitForText('Print', $this::TEXT_WAIT_TIMEOUT);
        $I->see('Download all');
        $I->see('The Tester Player000');
        $I->see('Tester Player000');
        $I->see('(218)-444-004');
        $I->see('Tester Player004');
    }
    /**
     * @param AcceptanceTester $I
     * @todo Player is not working yet.
     */
    public function checkIfPlayerWorksCorrectly(AcceptanceTester $I)
    {
//        $I->wantTo('go to the game controller and try to print one of the elements and see if the response is correct');
//        $I->amOnPage('/app/manage/team');
//        $I->see('Create a new Team');
//        $I->click(".print-TesterTeam");
//        $I->see('Print');
//        $I->see('Download all');
//        $I->see('The Tester Player000');
//        $I->see('Tester Player000');
//        $I->see('(218)-444-028');
//        $I->see('Tester Player034');
    }

}
