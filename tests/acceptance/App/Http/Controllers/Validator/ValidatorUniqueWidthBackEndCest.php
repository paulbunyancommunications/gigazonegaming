<?php
namespace Tests\Acceptance;
use \AcceptanceTester;

/**
 * Class ValidatorUniqueWidthBackEndCest
 * @package Tests\Acceptance
 */
class ValidatorUniqueWidthBackEndCest extends \BaseAcceptance
{
    /**
     *
     */
    const DEFAULT_WAIT = 15;
    const TEAM_A_NAME = "sdgkldR34 4fjglkjahL KDNFLKDFJG3245ERGRPIOS2JGLNSD23LKVNS KDJ0FN SK234D3JGNDFDFSFD23SD";
    const TEAM_B_NAME = "FLKFJG245ERGRPIOkldR34 KDJ0NFLKD 4glkahL KD345NS2JGLD23LKVNS FJ3245ERPIO D3JGNFFSFD2SD";

    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
        parent::_after($I);
    }

    /**
     * Test the form with the participation flag
     * @param AcceptanceTester $I
     */
    public function tryToCheckIfAfterCreatingATeamICanAssignItTheSameName(AcceptanceTester $I)
    {

    }
}
