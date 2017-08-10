<?php
namespace Tests\Acceptance\App\Http\Controllers\Backend\Manage;

use \AcceptanceTester;
use \BaseAcceptance;

/**
 * Class SearchControllerForPlayerCest
 * @package Tests\Acceptance\App\Http\Controllers\Backend\Manage
 */
class SearchControllerForPlayerCest extends BaseAcceptance
{

    /**
     * @param AcceptanceTester $I
     */
    public function _before(AcceptanceTester $I)
    {
        parent::_before($I);
        $this->populateDB($I);
        $this->loginWithAdminUser($I);
        $I->amOnPage('/app/manage/player');

    }

    /**
     * Create the test admin user
     */
    protected function populateDB(AcceptanceTester $I)
    {
//        $I->runShellCommand('cd /var/www');
        exec('php artisan db:seed --class=DatabaseSeeder');
        exec('php artisan db:seed --class=SearchControllerSeeder');
        $this->faker = \Faker\Factory::create();
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
     */
    public function testSearchControllerGetAPlayerByFullNameSearch(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Check that we can get a player from the search bar by full name');
        $I->amOnPage('/app/manage/player');
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $username = $faker->userName;
        $email = $faker->email;
        $I->fillField(['id' => 'name'], $firstName . ' ' . $lastName);
        $I->fillField(['id' => 'username'], $username);
        $I->fillField(['id' => 'email'], $email);
        $I->click(['id' => 'submit']);

        /** DO by full first name and last name */
        $I->fillField(['id' => 'searchText'], $firstName . ' ' . $lastName);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->wait(1);
        $I->see($firstName . ' ' . $lastName, 'a[title="Edit player '. $firstName . ' ' . $lastName .'"]');

    }

    /**
     * Check that we can get a player from the search bar by email
     * @test
     * @group SearchController
     * @group SearchControllerForPlayer
     */
    public function testSearchControllerGetAPlayerByEmailSearch(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Check that we can get a player from the search bar by email');
        $I->amOnPage('/app/manage/player');
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $username = $faker->userName;
        $email = $faker->email;
        $I->fillField(['id' => 'name'], $firstName . ' ' . $lastName);
        $I->fillField(['id' => 'username'], $username);
        $I->fillField(['id' => 'email'], $email);
        $I->click(['id' => 'submit']);

        /** Do search by email */
        $I->fillField(['id' => 'searchText'], $email);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->wait(1);
        $I->see($firstName . ' ' . $lastName, 'a[title="Edit player '. $firstName . ' ' . $lastName .'"]');

    }

    /**
     * Check that we can get a player from the search bar by email
     * @test
     * @group SearchController
     * @group SearchControllerForPlayer
     */
    public function testSearchControllerGetAPlayerByUserNameSearch(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Check that we can get a player from the search bar by username');
        $I->amOnPage('/app/manage/player');
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $username = $faker->userName;
        $email = $faker->email;
        $I->fillField(['id' => 'name'], $firstName . ' ' . $lastName);
        $I->fillField(['id' => 'username'], $username);
        $I->fillField(['id' => 'email'], $email);
        $I->click(['id' => 'submit']);

        /** Do search by username */
        $I->fillField(['id' => 'searchText'], $username);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->wait(1);
        $I->see($firstName . ' ' . $lastName, 'a[title="Edit player '. $firstName . ' ' . $lastName .'"]');

    }

    /**
     * Check that we can get a player from the search bar by first name only
     *
     * @test
     * @group SearchController
     * @group SearchControllerForPlayer
     */
    public function testSearchControllerGetAPlayerByFirstNameSearch(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Check that we can get a player from the search bar by first name only');
        $I->amOnPage('/app/manage/player');
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $username = $faker->userName;
        $email = $faker->email;
        $I->fillField(['id' => 'name'], $firstName . ' ' . $lastName);
        $I->fillField(['id' => 'username'], $username);
        $I->fillField(['id' => 'email'], $email);
        $I->click(['id' => 'submit']);

        /** Do search by first name only */
        $I->fillField(['id' => 'searchText'], $firstName);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->wait(1);
        $I->see($firstName . ' ' . $lastName, 'a[title="Edit player '. $firstName . ' ' . $lastName .'"]');

    }

    /**
     * Check that we can get a player from the search bar by last name only
     * @test
     * @group SearchController
     * @group SearchControllerForPlayer
     */
    public function testSearchControllerGetAPlayerByLastNameSearch(AcceptanceTester $I)
    {
        $faker = \Faker\Factory::create();
        $I->wantTo('Check that we can get a player from the search bar by last name only');
        $I->amOnPage('/app/manage/player');
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $username = $faker->userName;
        $email = $faker->email;
        $I->fillField(['id' => 'name'], $firstName . ' ' . $lastName);
        $I->fillField(['id' => 'username'], $username);
        $I->fillField(['id' => 'email'], $email);
        $I->click(['id' => 'submit']);

        /** Do search by last name only */
        $I->fillField(['id' => 'searchText'], $lastName);
        $I->click(['id' => 'doSearchBoxSubmit']);
        $I->waitForElementVisible(['id' => 'searchResults'], BaseAcceptance::TEXT_WAIT_TIMEOUT);
        $I->wait(1);
        $I->see($firstName . ' ' . $lastName, 'a[title="Edit player '. $firstName . ' ' . $lastName .'"]');

    }

}
