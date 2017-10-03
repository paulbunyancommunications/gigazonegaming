<?php

namespace Test\Acceptance\App\Http\Controllers\TeamDisplay;

use AcceptanceTester;

class ChampionOverridePageCest extends \BaseAcceptance
{
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->populateDB($I);
        $I->loginToWordpress($I, "admin","password",3);
        $I->amOnPage('/app/gamedisplay/override');
        $I->waitForJs('return jQuery.active == 0', 10);
    }

    public function _after(AcceptanceTester $I)
    {
        parent::_after($I);
    }

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

#Tests
#-------------------------------------------------------------------------
    public function seeChampionOverrideTitle(AcceptanceTester $I)
    {
        $I->see('Champion Override', 'h1');
    }

    public function tryToClickSubmitWithOutAllTheFeildsFilled(AcceptanceTester $I){
        $I->click('#SubmitChamps');
        $I->waitForText('All Fields Must Be Filled', 10, 'h3');
    }
    public function tryToSelectTeam1OrTeam2(AcceptanceTester $I){
        $I->selectOption('#Team','Team 1');
        $I->selectOption('#Team','Team 2');
    }
    public function tryToSubmitTeam1andTeam2Champions(AcceptanceTester $I){
        $I->wantTo('Submit Champions and see the correct response "Team 1 Champions Successfully Updated!!"');
        $I->selectOption('#Team','Team 1');
        $I->selectOption('#player1','Ashe');
        $I->selectOption('#player2','Ashe');
        $I->selectOption('#player3','Ashe');
        $I->selectOption('#player4','Ashe');
        $I->selectOption('#player5','Ashe');
        $I->click('#SubmitChamps');
        $I->waitForText('Team 1 Champions Successfully Updated!!', 30, 'h3');
    }
}