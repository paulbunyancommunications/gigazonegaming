<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use \AcceptanceTester;
use \BaseAcceptance;

/**
 * Class GamesControllerCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class GamesControllerCest extends BaseAcceptance
{

    /**
     * @param AcceptanceTester $I
     */
    public $faker;
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->loginWithAdminUser($I);
        $I->amOnPage('/app/manage/game');

    }

    /**
     * @param AcceptanceTester $I
     */
    public function _after(AcceptanceTester $I)
    {
        $this->logoutOfWp($I);
        parent::_after($I);
    }


    /**
     * @param AcceptanceTester $I
     * Get to the game management page and check for the titles
     */
    public function tryToGetToTheGameApp(AcceptanceTester $I)
    {
        $I->wantTo('get to the game management page');
        $I->see('Create a new Game');
        $I->see('Game List');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToCreateAGame(AcceptanceTester $I)
    {
        $I->wantTo('create a game on the management page');
        list($name, $title, $uri, $desc) = $this->createAGame($I);
        // check return, we should have a message and all the fields filled
        $I->see('The game '.$title.' was added');
        $I->seeInField(['id' => 'name'], $name);
        $I->seeInField(['id' => 'title'], $title);
        $I->seeInField(['id' => 'uri'], $uri);
        $I->seeInField(['id' => 'description'], $desc);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryToUpdateAGame(AcceptanceTester $I)
    {
        $I->wantTo('update a game on the management page');

        $this->createAGame($I);

        $name2 = implode('-', $this->faker->words(3));
        $title2 = $this->faker->sentence;
        $uri2 = $this->faker->url;
        $desc2 = $this->faker->paragraph;

        // fill fields with new values
        $I->fillField(['id' => 'name'], $name2);
        $I->fillField(['id' => 'title'], $title2);
        $I->fillField(['id' => 'uri'], $uri2);
        $I->fillField(['id' => 'description'], $desc2);
        $I->click(['id' => 'submit']);

        // check that the fields are now all updated
        $I->see('Game '. $title2.' was updated');
        $I->seeInField(['id' => 'name'], $name2);
        $I->seeInField(['id' => 'title'], $title2);
        $I->seeInField(['id' => 'uri'], $uri2);
        $I->seeInField(['id' => 'description'], $desc2);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function tryAndUpdateAGameKeepingTheSameGameName(AcceptanceTester $I)
    {
        $I->wantTo('update a game ,keeping the same name and title but changing everything else.');

        // make a game, then update
        list($name, $title, $uri, $desc) = $this->createAGame($I);
        $uri2 = $this->faker->url;
        $desc2 = $this->faker->paragraph;
        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'title'], $title);
        $I->fillField(['id' => 'uri'], $uri2);
        $I->fillField(['id' => 'description'], $desc2);
        $I->click(['id' => 'submit']);

        // I don't see dupe errors
        $I->dontSee('The game name is is already being used.');
        $I->dontSee('The game title is is already being used.');

        // I see the right message and values in the fields
        $I->see('Game '. $title.' was updated');
        $I->seeInField(['id' => 'name'], $name);
        $I->seeInField(['id' => 'title'], $title);
        $I->seeInField(['id' => 'uri'], $uri2);
        $I->seeInField(['id' => 'description'], $desc2);

    }

    /**
     * Ensure when creating a game that the name is present
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenNameIsMissing(AcceptanceTester $I)
    {
        $I->click(['id' => 'submit']);
        $I->see('The game name is required.');
    }

    /**
     * Ensure when creating a game that the name isn't already being used
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenNameIsAlreadyUsed(AcceptanceTester $I)
    {
        list($name, $title, $uri, $desc) = $this->createAGame($I);
        $I->amOnPage('/app/manage/game');

        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'title'], $title);
        $I->fillField(['id' => 'uri'], $uri);
        $I->fillField(['id' => 'description'], $desc);
        $I->click(['id' => 'submit']);

        $I->click(['id' => 'submit']);
        $I->see('The game name is is already being used.');
    }




    /**
     * Ensure when creating a game that the title is present
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenTitleIsMissing(AcceptanceTester $I)
    {
        $I->click(['id' => 'submit']);
        $I->see('The game title is required.');
    }


    /**
     * Ensure when creating a game that the title isn't already being used
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenTitleIsAlreadyUsed(AcceptanceTester $I)
    {
        list($name, $title, $uri, $desc) = $this->createAGame($I);
        $I->amOnPage('/app/manage/game');

        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'title'], $title);
        $I->fillField(['id' => 'uri'], $uri);
        $I->fillField(['id' => 'description'], $desc);
        $I->click(['id' => 'submit']);

        $I->click(['id' => 'submit']);
        $I->see('The game title is already being used.');
    }

    /**
     * Ensure when creating a game that the uri is present
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenUriIsMissing(AcceptanceTester $I)
    {
        $I->click(['id' => 'submit']);
        $I->see('The game uri is required.');
    }

    /**
     * Check that when submitting a game that the URI is valid
     *
     * @param AcceptanceTester $I
     */
    public function seeErrorWhenUriIsNotAUrl(AcceptanceTester $I)
    {
        $I->fillField(['id' => 'uri'], $this->faker->md5);
        $I->click(['id' => 'submit']);
        $I->see('The game uri must be a valid URL.');
    }

    /**
     * @param AcceptanceTester $I
     * @return array
     */
    private function createAGame(AcceptanceTester $I, $attributes = [])
    {
        $name = array_key_exists('name', $attributes) ? $attributes['name'] : implode('-', $this->faker->words(3));
        $title = array_key_exists('title', $attributes) ? $attributes['title'] : $this->faker->sentence;
        $uri = array_key_exists('uri', $attributes) ? $attributes['uri'] : $this->faker->url;
        $desc = array_key_exists('description', $attributes) ? $attributes['description'] :$this->faker->paragraph;

        $I->fillField(['id' => 'name'], $name);
        $I->fillField(['id' => 'title'], $title);
        $I->fillField(['id' => 'uri'], $uri);
        $I->fillField(['id' => 'description'], $desc);
        $I->click(['id' => 'submit']);
        return array($name, $title, $uri, $desc);
    }
}
