<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use \AcceptanceTester;
use \BaseAcceptance;

class ScoreControllerCest extends BaseAcceptance
{
    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->populateDB($I);
        $this->loginWithAdminUser($I);
        $I->amOnPage('/app/manage/scores');
        $I->wait(2);
        \Helper\Acceptance::loadJQuery($I, '');
        $I->waitForElementNotVisible(['id' => 'loadingTournamentSpinner'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->waitForElementNotVisible(['id' => 'loadingPlayerSpinner'], BaseAcceptance::TEXT_WAIT_TIMEOUT);

    }

    public function _after(AcceptanceTester $I)
    {
        parent::_after($I);
        $this->logoutOfWp($I);
    }


    public function tryToCreateANewScore(AcceptanceTester $I)
    {

        $I->wantTo('Create a new score in the db');
        $tournament = $I->executeJS(
            '
            var tournament, 
                options = $("#tournament").children("option");
                
            var option = options[Math.floor(Math.random()*options.length)];
            return option.text();
        );
        ');

        dd($tournament);



    }
}
