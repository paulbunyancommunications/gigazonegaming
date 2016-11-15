<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;
use \AcceptanceTester;
use \BaseAcceptance;

/**
 * Class GamesControllerCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class EmailControllerCest extends BaseAcceptance
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
     * Check that when sending to a game list we get back the correct message
     * @group EmailController
     * @param AcceptanceTester $I
     */
    public function sendingToGameListWillReturnSuccessMessage(AcceptanceTester $I)
    {
        $I->wantTo('Check that when sending to a game list we get back the correct message');
        $I->amOnPage('app/manage/email');
        
        $I->see('Email Filter');
        $I->selectOption(['id' => 'game_sort'], 'tester-game');
        $I->click(['id' => 'get_game']);
        $I->see('email sender');
        $count = $I->executeJS('return document.getElementById("emailList").value.split(",").length;');
        $I->fillField(['id' => 'subject'], 'This is the subject');
        $I->fillField(['id' => 'message'], 'This is the body');
        $I->click(['id' => 'send']);
        $I->see('The email has being sent to '. $count .' recipient'. ($count > 1 ? 's' : null) .'!');
    }

    /**
     * Check that when sending to a tournament list we get back the correct message
     * @group EmailController
     * @param AcceptanceTester $I
     */
    public function sendingToTournamentListWillReturnSuccessMessage(AcceptanceTester $I)
    {
        $I->wantTo('Check that when sending to a tournament list we get back the correct message');
        $I->amOnPage('app/manage/email');
        
        $I->see('Email Filter');
        $I->selectOption(['id' => 'tournament_sort'], 'Tester Tournament');
        $I->click(['id' => 'get_tournament']);
        $I->see('email sender');
        $count = $I->executeJS('return document.getElementById("emailList").value.split(",").length;');
        $I->fillField(['id' => 'subject'], 'This is the subject');
        $I->fillField(['id' => 'message'], 'This is the body');
        $I->click(['id' => 'send']);
        $I->see('The email has being sent to '. $count .' recipient'. ($count > 1 ? 's' : null) .'!');
    }

    /**
     * Check that when sending to a team list we get back the correct message
     * @group EmailController
     * @param AcceptanceTester $I
     */
    public function sendingToTeamListWillReturnSuccessMessage(AcceptanceTester $I)
    {
        $I->wantTo('Check that when sending to a team list we get back the correct message');
        $I->amOnPage('app/manage/email');
        
        $I->see('Email Filter');
        $I->selectOption(['id' => 'team_sort'], 'Tester Team');
        $I->click(['id' => 'get_team']);
        $I->see('email sender');
        $count = $I->executeJS('return document.getElementById("emailList").value.split(",").length;');
        $I->fillField(['id' => 'subject'], 'This is the subject');
        $I->fillField(['id' => 'message'], 'This is the body');
        $I->click(['id' => 'send']);
        $I->see('The email has being sent to '. $count .' recipient'. ($count > 1 ? 's' : null) .'!');
    }

    /**
     * Check that when sending to a player we get back the correct message
     * @group EmailController
     * @param AcceptanceTester $I
     */
    public function sendingToPlayerListWillReturnSuccessMessage(AcceptanceTester $I)
    {
        $I->wantTo('Check that when sending to a player we get back the correct message');
        $I->amOnPage('app/manage/email');
        
        $I->see('Email Filter');
        $I->selectOption(['id' => 'player_sort'], 'Tester Player000');
        $I->click(['id' => 'get_player']);
        $I->see('email sender');
        $count = $I->executeJS('return document.getElementById("emailList").value.split(",").length;');
        $I->fillField(['id' => 'subject'], 'This is the subject');
        $I->fillField(['id' => 'message'], 'This is the body');
        $I->click(['id' => 'send']);
        $I->see('The email has being sent to '. $count .' recipient'. ($count > 1 ? 's' : null) .'!');
    }
}
