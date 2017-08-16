<?php
namespace Test\Acceptance\App\Http\Controllers\TeamDisplay;


use AcceptanceTester;

class TeamViewDisplayCest extends \BaseAcceptance
{
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $I->loginToWordpress($I, "admin","password",3);
        $this->populateDB($I);
    }

    public function _after(AcceptanceTester $I)
    {
        parent::_after($I);
        exec('php artisan migrate:refresh');
    }

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    protected function populateDB(AcceptanceTester $I)
    {
//        $I->runShellCommand('cd /var/www');
        exec('php artisan db:seed --class=DatabaseSeeder');
        $this->faker = \Faker\Factory::create();
    }

#test
#-----------------------------------------------------------------------------
    public function seeTeam1Display(AcceptanceTester $I){
        $I->wantTo('See that the default page is loaded for team1 since there is no cache for team1');

        $I->amOnPage('/app/GameDisplay/team1');
        $I->see('Please Wait For Game To Begin', 'h1');
    }
    public function seeTeam2Display(AcceptanceTester $I){
        $I->wantTo('See that the default page is loaded for team2 since there is no cache for team2');

        $I->amOnPage('/app/GameDisplay/team2');
        $I->see('Please Wait For Game To Begin', 'h1');
    }

    public function seeTeam1StatesDisplayed(AcceptanceTester $I)
    {
        $I->wantTo('Create Cache for team1 and team2 TeamDisplay and see the teams stats displayed. Also submit champion Override and see champions loaded into the page');

        //Cache team data
        $I->amOnPage('/app/GameDisplay/Admin');
        $I->waitForJs('return jQuery.active == 0', 10);
        $I->executejs("$.ajaxSetup({headers: {\"X-CSRF-TOKEN\": $(\"#hiddenToken\").text(),'Testing': true}});");
        $I->selectOption('#Tournament', 'Tester Tournament');
        $I->selectOption('#Team', 'Tester Team');
        $I->selectOption('#Team-1', 'Tester Team');
        $I->selectOption('#Color', 'Red');
        $I->selectOption('#Color-1', 'Blue');
        $I->click('Submit');
        $I->waitForText('UPDATED', 10, '.console-header');

        //See team data on pages team1 and team2
        $I->amOnPage('/app/GameDisplay/team1');
        $I->waitForText('That Team', 15, 'h1');
        $I->amOnPage('/app/GameDisplay/team2');
        $I->waitForText('Team Awesome', 15, 'h1');
        $I->wantTo('See Champions loaded into page');

        //Select champs team 1
        $I->amOnPage('/app/GameDisplay/override');
        $I->selectOption('#Team','Team 1');
        $I->fillField('#player1','Ashe');
        $I->fillField('#player2','Morgana');
        $I->fillField('#player3','Darius');
        $I->fillField('#player4','Nidalee');
        $I->fillField('#player5','Zed');
        $I->click('#SubmitChamps');
        $I->waitForText('Team 1 Champions Successfully Updated!!', 10, 'h3');

        //Select champs team 2
        $I->selectOption('#Team','Team 2');
        $I->fillField('#player1','Jinx');
        $I->fillField('#player2','Thresh');
        $I->fillField('#player3','Renekton');
        $I->fillField('#player4','Kindred');
        $I->fillField('#player5','Talon');
        $I->click('#SubmitChamps');
        $I->waitForText('Team 2 Champions Successfully Updated!!', 10, 'h3');

        //see champs on team 1
        $I->amOnPage('/app/GameDisplay/team1');
        $I->waitForText('Ashe', 15, 'h3');

        //see champs on team 2
        $I->amOnPage('/app/GameDisplay/team2');
        $I->waitForText('Jinx', 15, 'h3');
    }
    public function seeTeam1AndTeam2DisplayedOnCustomerPage(AcceptanceTester $I)
    {
        $I->wantTo('Create Cache for team1 and team2 TeamDisplay and see the teams names on the customer page');

        //Cache team data
        $I->amOnPage('/app/GameDisplay/Admin');
        $I->waitForJs('return jQuery.active == 0', 10);
        $I->executejs("$.ajaxSetup({headers: {\"X-CSRF-TOKEN\": $(\"#hiddenToken\").text(),'Testing': true}});");
        $I->selectOption('#Tournament', 'Tester Tournament');
        $I->selectOption('#Team', 'Tester Team');
        $I->selectOption('#Team-1', 'Tester Team');
        $I->selectOption('#Color', 'Red');
        $I->selectOption('#Color-1', 'Blue');
        $I->click('Submit');
        $I->waitForText('UPDATED', 15, '.console-header');

        $I->amOnPage('/app/GameDisplay/customer');
        $I->waitForText('That Team',15,'#team1');
        $I->see('Team Awesome','#team2');
    }

}