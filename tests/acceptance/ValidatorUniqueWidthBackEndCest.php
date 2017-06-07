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
    public function tryToTest(AcceptanceTester $I)
    {
    }
}
