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
        $I->see('Print');
        $I->see('Download all');
        $I->see('The Tester Player000');
        $I->see('Tester Player000');
        $I->see('(218)-444-028');
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
        $I->see('Print');
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
        $I->see('Print');
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


    private function checkJquery(AcceptanceTester $I){
        $I->waitForJS('if(!window.jQuery){'.
            'var script = document.createElement("script");'.
            'script.type = "text/javascript";'.
            'script.src = "https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js";'.
            'document.getElementsByTagName("head")[0].appendChild(script);'.
            'return true;};', 120);
    }
}
