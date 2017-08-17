<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage\SearchController;

use \AcceptanceTester;
use \BaseAcceptance;
use \Faker\Factory as Faker;

/**
 * Class SearchControllerForPlayerCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class SearchControllerForPlayerCest extends \BaseAcceptance
{
    public $faker;
    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->populateDB($I);
        $this->loginWithAdminUser($I);
        $this->faker = Faker::create();
        $I->amOnPage('/app/manage/player');
    }

    /**
     * Create the test admin user
     * @param AcceptanceTester $I
     */
    protected function populateDB(AcceptanceTester $I)
    {
        exec('php artisan db:seed --class=DatabaseSeeder');
        exec('php artisan db:seed --class=SearchControllerSeeder');
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
     * Check that we can get a player from the search bar by full name
     * @test
     * @group SearchController
     * @group SearchControllerForPlayer
     * @param AcceptanceTester $I
     */
    public function testSearchControllerGetAPlayerByFullNameSearch(AcceptanceTester $I)
    {
        $I->wantTo('Check that we can get a player from the search bar by full name');
        $I->amOnPage('/app/manage/player');
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $username = $this->faker->userName;
        $email = $this->faker->email;
        $I->fillField(['id' => 'name'], $firstName . ' ' . $lastName);
        $I->fillField(['id' => 'username'], $username);
        $I->fillField(['id' => 'email'], $email);
        $I->click(['id' => 'submit']);

//        dd("here");
        $I->waitForElementVisible('a[title="Edit player '. $firstName . ' ' . $lastName .'"]', $this::TEXT_WAIT_TIMEOUT);
        $I->see($firstName . ' ' . $lastName, 'a[title="Edit player '. $firstName . ' ' . $lastName .'"]');
//        $id = $I->waitForJS("return document.querySelector('[title=\"Edit player " . $firstName . " " . $lastName ."\"]').id;)", $this::TEXT_WAIT_TIMEOUT);
        /** DO by full first name and last name */
        $I->fillField(['id' => 'searchText'], $firstName . ' ' . $lastName);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->waitForElementVisible('a[title="Player '. $firstName . ' ' . $lastName .'"]', $this::TEXT_WAIT_TIMEOUT);
        $I->click('a[title="Player '. $firstName . ' ' . $lastName .'"]');
        $I->waitForText("Update Player:", $this::TEXT_WAIT_TIMEOUT * 4);
        $I->seeElement(["id"=>"name"],["value"=> "$firstName $lastName" ]);

    }

    /**
     * Check that we can get a player from the search bar by email
     * @test
     * @group SearchController
     * @group SearchControllerForPlayer
     * @param AcceptanceTester $I
     */
    public function testSearchControllerGetAPlayerByEmailSearch(AcceptanceTester $I)
    {
        $I->wantTo('Check that we can get a player from the search bar by email');
        $I->amOnPage('/app/manage/player');
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $username = $this->faker->userName;
        $email = $this->faker->email;
        $I->fillField(['id' => 'name'], $firstName . ' ' . $lastName);
        $I->fillField(['id' => 'username'], $username);
        $I->fillField(['id' => 'email'], $email);
        $I->click(['id' => 'submit']);

        $I->waitForElementVisible('a[title="Edit player '. $firstName . ' ' . $lastName .'"]', $this::TEXT_WAIT_TIMEOUT);
        $I->see($firstName . ' ' . $lastName, 'a[title="Edit player '. $firstName . ' ' . $lastName .'"]');
//        $id = $I->waitForJS("return document.querySelector('[title=\"Edit player " . $firstName . " " . $lastName ."\"]').id;)", $this::TEXT_WAIT_TIMEOUT);
        /** Do search by email */
        $I->fillField(['id' => 'searchText'], $email);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->waitForElementVisible('a[title="Player '. $firstName . ' ' . $lastName .'"]', $this::TEXT_WAIT_TIMEOUT);
        $I->click('a[title="Player '. $firstName . ' ' . $lastName .'"]');
        $I->waitForText("Update Player:", $this::TEXT_WAIT_TIMEOUT * 4);
        $I->seeElement(["id"=>"name"],["value"=> "$firstName $lastName" ]);

    }

    /**
     * Check that we can get a player from the search bar by email
     * @test
     * @group SearchController
     * @group SearchControllerForPlayer
     * @param AcceptanceTester $I
     */
    public function testSearchControllerGetAPlayerByUserNameSearch(AcceptanceTester $I)
    {
        $I->wantTo('Check that we can get a player from the search bar by username');
        $I->amOnPage('/app/manage/player');
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $username = $this->faker->userName;
        $email = $this->faker->email;
        $I->fillField(['id' => 'name'], $firstName . ' ' . $lastName);
        $I->fillField(['id' => 'username'], $username);
        $I->fillField(['id' => 'email'], $email);
        $I->click(['id' => 'submit']);

        $I->waitForElementVisible('a[title="Edit player '. $firstName . ' ' . $lastName .'"]', $this::TEXT_WAIT_TIMEOUT);
        $I->see($firstName . ' ' . $lastName, 'a[title="Edit player '. $firstName . ' ' . $lastName .'"]');
//        $id = $I->waitForJS("return document.querySelector('[title=\"Edit player " . $firstName . " " . $lastName ."\"]').id;)", $this::TEXT_WAIT_TIMEOUT);
        /** Do search by username */
        $I->fillField(['id' => 'searchText'], $username);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->waitForElementVisible('a[title="Player '. $firstName . ' ' . $lastName .'"]', $this::TEXT_WAIT_TIMEOUT);
        $I->click('a[title="Player '. $firstName . ' ' . $lastName .'"]');
        $I->waitForText("Update Player:", $this::TEXT_WAIT_TIMEOUT * 4);
        $I->seeElement(["id"=>"name"],["value"=> "$firstName $lastName" ]);
    }

    /**
     * Check that we can get a player from the search bar by first name only
     *
     * @test
     * @group SearchController
     * @group SearchControllerForPlayer
     * @param AcceptanceTester $I
     */
    public function testSearchControllerGetAPlayerByFirstNameSearch(AcceptanceTester $I)
    {
        $I->wantTo('Check that we can get a player from the search bar by first name only');
        $I->amOnPage('/app/manage/player');
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $username = $this->faker->userName;
        $email = $this->faker->email;
        $I->fillField(['id' => 'name'], $firstName . ' ' . $lastName);
        $I->fillField(['id' => 'username'], $username);
        $I->fillField(['id' => 'email'], $email);
        $I->click(['id' => 'submit']);


        $I->waitForElementVisible('a[title="Edit player '. $firstName . ' ' . $lastName .'"]', $this::TEXT_WAIT_TIMEOUT);
        $I->see($firstName . ' ' . $lastName, 'a[title="Edit player '. $firstName . ' ' . $lastName .'"]');
//        $id = $I->waitForJS("return document.querySelector('[title=\"Edit player " . $firstName . " " . $lastName ."\"]').id;)", $this::TEXT_WAIT_TIMEOUT);
        /** Do search by first name only */
        $I->fillField(['id' => 'searchText'], $firstName);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->waitForElementVisible('a[title="Player '. $firstName . ' ' . $lastName .'"]', $this::TEXT_WAIT_TIMEOUT);
        $I->click('a[title="Player '. $firstName . ' ' . $lastName .'"]');
        $I->waitForText("Update Player:", $this::TEXT_WAIT_TIMEOUT * 4);
        $I->seeElement(["id"=>"name"],["value"=> "$firstName $lastName" ]);

    }

    /**
     * Check that we can get a player from the search bar by last name only
     * @test
     * @group SearchController
     * @group SearchControllerForPlayer
     * @param AcceptanceTester $I
     */
    public function testSearchControllerGetAPlayerByLastNameSearch(AcceptanceTester $I)
    {
        $I->wantTo('Check that we can get a player from the search bar by last name only');
        $I->amOnPage('/app/manage/player');
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $username = $this->faker->userName;
        $email = $this->faker->email;
        $I->fillField(['id' => 'name'], $firstName . ' ' . $lastName);
        $I->fillField(['id' => 'username'], $username);
        $I->fillField(['id' => 'email'], $email);
        $I->click(['id' => 'submit']);

        $I->waitForElementVisible('a[title="Edit player '. $firstName . ' ' . $lastName .'"]', $this::TEXT_WAIT_TIMEOUT);
        $I->see($firstName . ' ' . $lastName, 'a[title="Edit player '. $firstName . ' ' . $lastName .'"]');
//        $id = $I->waitForJS("return document.querySelector('[title=\"Edit player " . $firstName . " " . $lastName ."\"]').id;)", $this::TEXT_WAIT_TIMEOUT);
        /** Do search by last name only */
        $I->fillField(['id' => 'searchText'], $lastName);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->waitForElementVisible('a[title="Player '. $firstName . ' ' . $lastName .'"]', $this::TEXT_WAIT_TIMEOUT);
        $I->click('a[title="Player '. $firstName . ' ' . $lastName .'"]');
        $I->waitForText("Update Player:", $this::TEXT_WAIT_TIMEOUT * 4);
        $I->seeElement(["id"=>"name"],["value"=> "$firstName $lastName" ]);

    }

}
