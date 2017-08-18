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
     * @skip it_has_a_valid_image_path: images aren't commited with tests and this tell will not pass till it founds the images as header doesnt show if there are no images
     */
    public function it_has_a_valid_image_path(AcceptanceTester $I, $scenario)
    {
        $I->amOnPage('/');
        $I->waitForElementVisible(".masthead-photo-rotator-item", $this::DEFAULT_WAIT * 2);
        $id = $I->executeJS('return $(".masthead-photo-rotator-item").first().children("img").first().attr("id")');
        \Codeception\Util\Debug::debug($id);
        $I->canSeeElementInDOM(['id' => $id]);
        $imgLink = $I->executeJS('return $("#'.$id.'").attr("src")');
        \Codeception\Util\Debug::debug($imgLink);
        $I->amOnPage($imgLink);
        $I->dontSee('Error');

    }

    /**
     * tests
     * @skip it_has_a_malformed_image_path: images aren't commited with tests and this tell will not pass till it founds the images as header doesnt show if there are no images
     */
    public function it_has_a_malformed_image_path(AcceptanceTester $I, $scenario)
    {
        $scenario->skip("images aren't commited with tests and this tell will not pass till it founds the images as header doesnt show if there are no images");
        $I->amOnPage('/');
        $I->waitForElementVisible(".masthead-photo-rotator-item", $this::DEFAULT_WAIT * 2);
        $id = $I->executeJS('return $(".masthead-photo-rotator-item").first().children("img").first().attr("id")');
        \Codeception\Util\Debug::debug($id);
        $I->canSeeElementInDOM(['id' => $id]);
        $imgLink = $I->executeJS('return $("#'.$id.'").attr("src")');
        \Codeception\Util\Debug::debug($imgLink);
        $imgLinkError = str_replace('image=', 'image=12345', $imgLink);
        \Codeception\Util\Debug::debug($imgLinkError);
        $I->amOnPage(parse_url($imgLinkError, PHP_URL_PATH) . '?' . parse_url($imgLinkError, PHP_URL_QUERY));
        $I->see('Error: malformed image path');

    }
    /**
     * tests
     * @skip it_is_linked_to_a_missing_image: images aren't commited with tests and this tell will not pass till it founds the images as header doesnt show if there are no images
     */
    public function it_is_linked_to_a_missing_image(AcceptanceTester $I, $scenario)
    {
        $scenario->skip("images aren't commited with tests and this tell will not pass till it founds the images as header doesnt show if there are no images");
        $I->amOnPage('/');
        $I->waitForElementVisible(".masthead-photo-rotator-item", $this::DEFAULT_WAIT * 2);
        $id = $I->executeJS('return $(".masthead-photo-rotator-item").first().children("img").first().attr("id")');
        \Codeception\Util\Debug::debug($id);
        $I->canSeeElementInDOM(['id' => $id]);
        $imgLink = $I->executeJS('return $("#'.$id.'").attr("src")');
        \Codeception\Util\Debug::debug($imgLink);
        $imgLinkError = str_replace('image=/', 'image=/12345/', $imgLink);
        \Codeception\Util\Debug::debug($imgLinkError);
        $I->amOnPage(parse_url($imgLinkError, PHP_URL_PATH) . '?' . parse_url($imgLinkError, PHP_URL_QUERY));
        $I->see('Error: image does not exist');
    }
}
