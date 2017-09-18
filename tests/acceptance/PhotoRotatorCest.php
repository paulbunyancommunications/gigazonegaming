<?php


class PhotoRotatorCest extends \BaseAcceptance
{
    const DEFAULT_WAIT = 15;

    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $I->runShellCommand('php artisan db:seed --class=PhotoRotatorCestImageSeed');
    }

    public function _after(AcceptanceTester $I)
    {
        parent::_after($I);
    }

    /**
     * tests
     * @group photoRotator
     * @test
     */
    public function it_has_a_valid_image_path(AcceptanceTester $I, $scenario)
    {
        $imgLink = $this->iInitiallyCheckThatTheRotatorsExists($I);
        $I->amOnPage($imgLink);
        $I->dontSee('Error');

    }

    /**
     * tests
     * @group photoRotator
     * @test
     */
    public function it_has_a_malformed_image_path(AcceptanceTester $I, $scenario)
    {
        $imgLink = $this->iInitiallyCheckThatTheRotatorsExists($I);
        $imgLinkError = str_replace('image=', 'image=12345', $imgLink);
        \Codeception\Util\Debug::debug($imgLinkError);
        $I->amOnPage(parse_url($imgLinkError, PHP_URL_PATH) . '?' . parse_url($imgLinkError, PHP_URL_QUERY));
        $I->see('Error: malformed image path');

    }
    /**
     * tests
     * @group photoRotator
     * @test
     */
    public function it_is_linked_to_a_missing_image(AcceptanceTester $I, $scenario)
    {
        $imgLink = $this->iInitiallyCheckThatTheRotatorsExists($I);
        $imgLinkError = str_replace('image=/', 'image=/12345/', $imgLink);
        \Codeception\Util\Debug::debug($imgLinkError);
        $I->amOnPage(parse_url($imgLinkError, PHP_URL_PATH) . '?' . parse_url($imgLinkError, PHP_URL_QUERY));
        $I->see('Error: image does not exist');
    }

    /**
     * @param AcceptanceTester $I
     * @return mixed
     */
    protected function iInitiallyCheckThatTheRotatorsExists(AcceptanceTester $I): string
    {
        $I->amOnPage('/');
        $I->wait(self::DEFAULT_WAIT);
        $I->seeElementInDOM(".masthead-photo-rotator-item");
        $id = $I->executeJS('return $(".masthead-photo-rotator-item").first().children("img").first().attr("id")');
        \Codeception\Util\Debug::debug($id);
        $I->canSeeElementInDOM(['id' => $id]);
        $imgLink = $I->executeJS('return $("#' . $id . '").attr("src")');
        \Codeception\Util\Debug::debug($imgLink);
        return $imgLink;
    }
}
