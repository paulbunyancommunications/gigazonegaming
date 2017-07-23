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
    {   $I->wantTo('Create Cache for team1 and team2 TeamDisplay and see the teams stats displayed. Also submit champion Override and see champions loaded into the page');
        $I->amOnPage('/app/GameDisplay/Admin');
        $I->waitForJs('return jQuery.active == 0', 10);
        $I->executejs("$('head').append('<meta content=\"true\" name=\"Testing\">');");
        $I->waitForElement("meta[name='Testing']",10);
        $I->selectOption('#Tournament', 'Tester Tournament');
        $I->selectOption('#Team', 'Tester Team');
        $I->selectOption('#Team-1', 'Tester Team');
        $I->selectOption('#Color', 'Red');
        $I->selectOption('#Color-1', 'Blue');
        $I->click('Submit');
        $I->waitForText('UPDATED', 10, '.console-header');
        $I->amOnPage('/app/GameDisplay/team1');
        $I->waitForText('Team Awesome', 15, 'h1');
        $I->amOnPage('/app/GameDisplay/team2');
        $I->waitForText('Team Awesome', 15, 'h1');
        $I->wantTo('See Champions loaded into page');
        $I->amOnPage('/app/GameDisplay/override');
        $I->selectOption('#Team','Team 1');
        $I->fillField('#player1','Ashe');
        $I->fillField('#player2','Ashe');
        $I->fillField('#player3','Ashe');
        $I->fillField('#player4','Ashe');
        $I->fillField('#player5','Ashe');
        $I->click('#SubmitChamps');
        $I->waitForText('Team 1 Champions Successfully Updated!!', 10, 'h3');
        $I->amOnPage('/app/GameDisplay/team1');
        $I->waitForText('Ashe', 15, 'h3');
    }

}