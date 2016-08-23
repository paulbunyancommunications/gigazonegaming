<?php


class PhotoRotatorCest
{
    const DEFAULT_WAIT = 15;

    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function testThatTheUrlForTheResponsiveImageIsValid(AcceptanceTester $I)
    {

        $I->amOnPage('/');
        $I->wait(self::DEFAULT_WAIT);
        $id = $I->executeJS('return $(".masthead-photo-rotator-item:first-child").first("img").attr("id")');
        $I->canSeeElementInDOM(['id' => $id]);
        $imgLink = $I->executeJS('return $("#"'.$id.').attr("src")');
        $I->amOnPage($imgLink);
        $I->dontSee('Error');


    }
}
