<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use \AcceptanceTester;
use \BaseAcceptance;

class IndividualPlayersControllerCest extends BaseAcceptance
{

    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->loginWithAdminUser($I);
        $I->amOnPage('/app/manage/individualPlayer');

    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
        $this->logoutOfWp($I);
        parent::_after($I);
    }

    public function tryToAttachPlayerToSomething(AcceptanceTester $I, $scenario)
    {
        /** @todo remove this once the individual controller is complete */
        $scenario->skip();
    }


}
