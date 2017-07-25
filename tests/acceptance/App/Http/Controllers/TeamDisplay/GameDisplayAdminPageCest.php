<?php
namespace Test\Acceptance\App\Http\Controllers\TeamDisplay;


use AcceptanceTester;

class GameDisplayAdminPageCest extends \BaseAcceptance
{
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->populateDB($I);
        $I->loginToWordpress($I, "admin","password",3);
        $I->amOnPage('/app/GameDisplay/Admin');
        $I->waitForJs('return jQuery.active == 0', 10);
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

// tests
#------------------------------------------------------------------------
    public function seeAdminPage(AcceptanceTester $I){
        $I->wantTo('see Team 1 on page load');
        $I->see('Team 1');
    }

    public function seeSubmitButtonIsDisabled(AcceptanceTester $I){
        $I->wantTo('see Submit button is disabled on page load');
        $I->grabAttributeFrom('#submit', 'disabled');
    }

    public function seeCacheWasSuccessfullyClearedAfterClickingClearCache(AcceptanceTester $I){
        $I->wantTo('Be notified that the cache is cleared after clicking clear cache');
        $I->click('Clear Cache');
        $I->waitForText('Cache Successfully Cleared', 10, '#info');
    }

    public function tryToClickGetChampionsButtonWithOutSelectingATeam(AcceptanceTester $I){
        $I->click('Get Champions');
        $I->waitForText('The cache is not available. Please Select a team and a color before getting champions.', 15, '#info');
    }


    Public function tryToSelectTeamsAndColorsAfterTournamentIsSelected(AcceptanceTester $I){
        $I->selectOption('#Tournament', 'gigazone-gaming-2016-league-of-legends');
        $I->selectOption('#Team', 'Tester Team');
        $I->selectOption('#Team-1', 'Tester Team');
        $I->selectOption('#Color', 'Red');
        $I->selectOption('#Color-1', 'Blue');
    }

//    public function seeChampionOverrideTitleAfterClickingChampionOverrideButton(AcceptanceTester $I){
//        $I->click('Champion Override');
//        $I->waitForText('Champion Override', 10, 'h1');
//        //need to see if $I is on another page now instead of admin page
//    }

    public function tryToSubmitTheCache(AcceptanceTester $I)
    {
        $I->wantTo('Click the Submit Cache button and assert that there is the correct data cached');
        $I->executejs("$.ajaxSetup({headers: {\"X-CSRF-TOKEN\": $(\"#hiddenToken\").text(),'Testing': true}});");
        $I->selectOption('#Tournament', 'Tester Tournament');
        $I->selectOption('#Team', 'Tester Team');
        $I->selectOption('#Team-1', 'Tester Team');
        $I->selectOption('#Color', 'Red');
        $I->selectOption('#Color-1', 'Blue');
        $I->click('Submit');
        $I->waitForText('UPDATED', 10, '.console-header');
    }
}